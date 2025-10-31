<?php
// app/Http/Controllers/HasilAkhirExportController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Karyawan;
use App\Models\SubKriteria;

class HasilAkhirExportController extends Controller
{
    public function export(Request $request, Karyawan $karyawan)
    {
        $tahun     = (int)($request->query('tahun') ?? date('Y'));
        $noDok     = $request->query('no') ?? strtoupper(substr($karyawan->kode,0,2)).'-'.$tahun.'-'.sprintf('%02d', now()->month);
        $threshold = 0.80; // 80%

        // 1) Ambil sub-kriteria untuk jabatan karyawan (urut per kriteria & sub)
        $subs = SubKriteria::selectRaw('sub_kriteria.*, kriteria.kode_kriteria, kriteria.nama_kriteria as nama_kriteria, kriteria.tipe, kriteria.bobot as w_kriteria')
            ->join('kriteria','kriteria.id','=','sub_kriteria.kriteria_id')
            ->where('kriteria.jabatan', $karyawan->jabatan)
            ->orderBy('kriteria.kode_kriteria')->orderBy('sub_kriteria.kode_sub')->get();

        if ($subs->isEmpty()) {
            return back()->withErrors('Belum ada sub-kriteria untuk jabatan '.$karyawan->jabatan);
        }

        // 2) KPI 12 bulan (0..1) untuk karyawan ini
        //    Bentuk: $kpi[sub_id][1..12] = 0..1 atau null
        $kpi = [];
        foreach ($subs as $s) { $kpi[$s->id] = array_fill(1,12,null); }

        $rows = DB::table('kpi_nilais')
            ->select('sub_kriteria_id','bulan','nilai')
            ->where('karyawan_id',$karyawan->id)->where('tahun',$tahun)
            ->whereIn('sub_kriteria_id',$subs->pluck('id'))
            ->get();

        foreach ($rows as $r) { $kpi[$r->sub_kriteria_id][(int)$r->bulan] = (float)$r->nilai; }

        // 3) Rata-rata tahunan per sub (avg 0..1), lalu dikali bobot sub → avgWeighted
        $avg = []; $avgWeighted = []; $roundedW = [];
        foreach ($subs as $s) {
            $vals = array_filter($kpi[$s->id], fn($v)=>$v!==null); // abaikan bulan kosong
            $avgSub = $vals ? array_sum($vals)/count($vals) : null;
            $avg[$s->id] = $avgSub;
            $avgWeighted[$s->id] = is_null($avgSub) ? null : $avgSub * (float)$s->bobot;

            // konsisten dengan tampilan/Excel: bulatkan ke persen bulat sebelum normalisasi
            $roundedW[$s->id] = is_null($avgWeighted[$s->id]) ? null
                : round($avgWeighted[$s->id]*100,0,PHP_ROUND_HALF_UP)/100; // 0..1
        }

        // 4) min/max per sub untuk normalisasi (menggunakan roundedW) berdasarkan jabatan yg sama
        $peerRows = DB::table('kpi_nilais')
            ->select('karyawan_id','sub_kriteria_id', DB::raw('AVG(nilai) as avg_nilai'))
            ->where('tahun',$tahun)
            ->whereIn('sub_kriteria_id',$subs->pluck('id'))
            ->join('karyawans','karyawans.id','=','kpi_nilais.karyawan_id')
            ->where('karyawans.jabatan',$karyawan->jabatan)
            ->groupBy('karyawan_id','sub_kriteria_id')
            ->get();

        // kumpulkan avgWeighted(rounded) untuk peer satu jabatan
        $peerPerSub = [];
        foreach ($subs as $s) { $peerPerSub[$s->id] = []; }
        foreach ($peerRows as $r) {
            $w = (float)$subs->firstWhere('id',$r->sub_kriteria_id)->bobot; // 0..1
            $rw = round($r->avg_nilai * $w * 100,0,PHP_ROUND_HALF_UP)/100;
            $peerPerSub[$r->sub_kriteria_id][] = $rw;
        }

        $max=[]; $min=[];
        foreach ($subs as $s) {
            $vals = $peerPerSub[$s->id] ?? [];
            $max[$s->id] = $vals ? max($vals) : 0.0;
            $min[$s->id] = $vals ? min($vals) : 0.0;
        }

        // 5) Normalisasi karyawan ini (pakai roundedW)
        $norm = [];
        foreach ($subs as $s) {
            $v = $roundedW[$s->id];
            if ($v === null) { $norm[$s->id] = null; continue; }
            $norm[$s->id] = ($s->tipe==='benefit')
                ? ($max[$s->id] > 0 ? $v / $max[$s->id] : null)
                : (($v > 0 && $min[$s->id] > 0) ? $min[$s->id] / $v : null);
        }
        // fallback kalau cuma satu alternatif (jarang terjadi di export personal)
        if (count(array_filter($peerPerSub)) < 2) { $norm = $roundedW; }

        // 6) Preferensi V: avg per kriteria × bobot kriteria
        $byKriteria = $subs->groupBy('kriteria_id');
        $score = 0.0;
        foreach ($byKriteria as $kid => $subsInK) {
            $wK = (float)$subsInK->first()->w_kriteria; // 0..1
            $sumK = 0; $cntK = 0;
            foreach ($subsInK as $s) {
                $r = $norm[$s->id];
                if ($r===null) continue;
                $sumK += $r; $cntK++;
            }
            if ($cntK>0) { $score += ($sumK/$cntK) * $wK; }
        }
        $scorePct = round($score*100, 0, PHP_ROUND_HALF_UP);
        $eligible = $score >= $threshold;

        // 7) Render PDF
        $pdf = Pdf::loadView('pdf.hasil_kpi', [
            'noDok'     => $noDok,
            'tanggal'   => now()->translatedFormat('d F Y'),
            'karyawan'  => $karyawan,
            'tahun'     => $tahun,
            'subs'      => $subs,
            'kpi'       => $kpi,          // 0..1 per bulan
            'avg'       => $avg,          // 0..1
            'roundedW'  => $roundedW,     // 0..1 (dibulatkan)
            'norm'      => $norm,         // 0..1
            'scorePct'  => $scorePct,     // %
            'eligible'  => $eligible,
            'threshold' => (int)($threshold*100),
        ])->setPaper('a4','landscape');

        $filename = 'KPI-'.$karyawan->kode.'-'.$tahun.'.pdf';
        return $pdf->download($filename);
    }
}
