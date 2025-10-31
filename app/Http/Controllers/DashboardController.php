<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Karyawan;
use App\Models\SubKriteria;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun   = (int)($request->query('tahun') ?? date('Y'));
        $jabatan = $request->query('jabatan') ?? 'Operator';
        $threshold = 0.80;

        // --- ambil sub & karyawan sesuai jabatan
        $subs = SubKriteria::selectRaw('sub_kriteria.*, kriteria.tipe, kriteria.bobot as w_kriteria')
            ->join('kriteria','kriteria.id','=','sub_kriteria.kriteria_id')
            ->where('kriteria.jabatan',$jabatan)
            ->orderBy('kriteria.kode_kriteria')->orderBy('sub_kriteria.kode_sub')->get();

        $karyawans = Karyawan::where('jabatan',$jabatan)->orderBy('kode')->orderBy('nama')->get();

        // --- AVG nilai 0..1 per (karyawan, sub) untuk tahun tsb
        $rows = DB::table('kpi_nilais')
            ->select('karyawan_id','sub_kriteria_id', DB::raw('AVG(nilai) as avg_nilai'))
            ->where('tahun',$tahun)
            ->whereIn('sub_kriteria_id',$subs->pluck('id'))
            ->whereIn('karyawan_id',$karyawans->pluck('id'))
            ->groupBy('karyawan_id','sub_kriteria_id')
            ->get();

        // --- matriks avgWeighted (dibulatkan ke persen bulat → balik 0..1)
        $avgW = []; $roundedW = [];
        $wSub = $subs->pluck('bobot','id')->all(); // 0..1

        foreach ($karyawans as $kar) {
            $avgW[$kar->id] = array_fill_keys($subs->pluck('id')->all(), null);
            $roundedW[$kar->id] = array_fill_keys($subs->pluck('id')->all(), null);
        }
        foreach ($rows as $r) {
            $avg = (float)$r->avg_nilai;
            $v   = $avg * (float)$wSub[$r->sub_kriteria_id];
            $avgW[$r->karyawan_id][$r->sub_kriteria_id]     = $v;
            $roundedW[$r->karyawan_id][$r->sub_kriteria_id] = round($v*100,0,PHP_ROUND_HALF_UP)/100;
        }

        // --- min/max per sub dari roundedW
        $max=[]; $min=[];
        foreach ($subs as $s) {
            $vals = [];
            foreach ($karyawans as $kar) {
                $v = $roundedW[$kar->id][$s->id];
                if ($v !== null) $vals[] = $v;
            }
            $max[$s->id] = $vals ? max($vals) : 0.0;
            $min[$s->id] = $vals ? min($vals) : 0.0;
        }

        // --- normalisasi SAW (pakai roundedW)
        $norm = [];
        foreach ($karyawans as $kar) {
            $norm[$kar->id] = [];
            foreach ($subs as $s) {
                $v = $roundedW[$kar->id][$s->id];
                if ($v === null) { $norm[$kar->id][$s->id] = null; continue; }
                $norm[$kar->id][$s->id] = $s->tipe === 'benefit'
                    ? ($max[$s->id] > 0 ? $v / $max[$s->id] : null)
                    : (($v > 0 && $min[$s->id] > 0) ? $min[$s->id] / $v : null);
            }
        }
        if ($karyawans->count() < 2) { $norm = $roundedW; } // fallback

        // --- Preferensi V: avg per kriteria × bobot_kriteria (sekali)
        $byKriteria = $subs->groupBy('kriteria_id');
        $scores = []; // [kid => score 0..1]
        foreach ($karyawans as $kar) {
            $score = 0.0;
            foreach ($byKriteria as $kid => $subsInK) {
                $wK = (float)$subsInK->first()->w_kriteria;
                $sumK=0; $cntK=0;
                foreach ($subsInK as $s) {
                    $rij = $norm[$kar->id][$s->id]; if ($rij === null) continue;
                    $sumK += $rij; $cntK++;
                }
                if ($cntK > 0) { $score += ($sumK/$cntK) * $wK; }
            }
            $scores[$kar->id] = $score;
        }

        // --- kartu KPI
        $totalKar   = $karyawans->count();
        $eligible   = collect($scores)->filter(fn($v)=>$v >= $threshold)->count();
        $ineligible = $totalKar - $eligible;
        $avgScore   = $totalKar ? (array_sum($scores)/$totalKar) : 0.0;

        // --- top 5 & bottom 5
        $ranked = collect($scores)->map(function($v,$kid) use ($karyawans){
            $k = $karyawans->firstWhere('id',$kid);
            return ['id'=>$kid,'kode'=>$k->kode,'nama'=>$k->nama,'score'=>$v];
        })->sortByDesc('score')->values();

        $top5    = $ranked->take(3);
        $bottom5 = $ranked->slice(max(0,$ranked->count()-3))->values();

        // --- data untuk chart (distribusi eligibility & rata-rata per kriteria)
        $dist = ['eligible'=>$eligible, 'ineligible'=>$ineligible];
        $avgPerKriteria = $byKriteria->map(function($subsInK) use ($karyawans,$norm){
            $sum=0; $cnt=0;
            foreach ($karyawans as $kar) {
                $lokal=0; $n=0;
                foreach ($subsInK as $s) { $v=$norm[$kar->id][$s->id]; if($v!==null){$lokal+=$v; $n++;} }
                if ($n>0) { $sum += $lokal/$n; $cnt++; }
            }
            return $cnt? $sum/$cnt : 0.0; // 0..1
        });

        return view('Dash', [
            'tahun'   => $tahun,
            'jabatan' => $jabatan,
            'threshold' => $threshold*100,
            'cards' => [
                'totalKar'   => $totalKar,
                'eligible'   => $eligible,
                'ineligible' => $ineligible,
                'avgScore'   => round($avgScore*100,2),
            ],
            'top5'    => $top5,
            'bottom5' => $bottom5,
            'dist'    => $dist,
            'avgPerKriteria' => $avgPerKriteria, // label = kriteria_id; di blade tampilkan kode_kriteria
            'subs'    => $subs, // agar bisa mapping nama/kode
        ]);
    }
}
