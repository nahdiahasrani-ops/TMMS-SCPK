<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Karyawan;
use App\Models\SubKriteria;

class HasilAkhirController extends Controller
{
    public function index(Request $request)
    {
        $tahun     = (int)($request->query('tahun')   ?? date('Y'));
        $jabatan   =        $request->query('jabatan') ?? 'Operator';
        $q         = trim((string)($request->query('q') ?? ''));
        $filter    = $request->query('filter'); // eligible|ineligible|highest|lowest|null
        $threshold = 0.80; // 80%

        // Sub-kriteria sesuai jabatan + bobot kriteria
        $subs = SubKriteria::selectRaw(
                    'sub_kriteria.*, kriteria.tipe, kriteria.bobot as w_kriteria'
                )
                ->join('kriteria','kriteria.id','=','sub_kriteria.kriteria_id')
                ->where('kriteria.jabatan',$jabatan)
                ->orderBy('kriteria.kode_kriteria')
                ->orderBy('sub_kriteria.kode_sub')
                ->get();

        // Karyawan sesuai jabatan (+ pencarian)
        $karyawansQ = Karyawan::where('jabatan',$jabatan);
        if ($q !== '') {
            $karyawansQ->where(function($qq) use ($q){
                $qq->where('kode','like',"%$q%")->orWhere('nama','like',"%$q%");
            });
        }
        $karyawans = $karyawansQ->orderBy('kode')->orderBy('nama')->get();

        if ($karyawans->isEmpty() || $subs->isEmpty()) {
            return view('hasil.index', [
                'tahun'=>$tahun,'jabatan'=>$jabatan,'q'=>$q,'filter'=>$filter,
                'rows'=>collect(), 'threshold'=>$threshold*100, 'subs'=>$subs
            ]);
        }

        // AVG nilai 0..1 per (karyawan, sub) pada tahun tsb
        $rowsAvg = DB::table('kpi_nilais')
            ->select('karyawan_id','sub_kriteria_id', DB::raw('AVG(nilai) as avg_nilai'))
            ->where('tahun',$tahun)
            ->whereIn('sub_kriteria_id',$subs->pluck('id'))
            ->whereIn('karyawan_id',$karyawans->pluck('id'))
            ->groupBy('karyawan_id','sub_kriteria_id')
            ->get();

        // Matriks avgWeighted = avg * bobot_sub
        $avgWeighted = [];
        $wSub = $subs->pluck('bobot','id')->all(); // 0..1
        foreach ($karyawans as $kar) {
            $avgWeighted[$kar->id] = array_fill_keys($subs->pluck('id')->all(), null);
        }
        foreach ($rowsAvg as $r) {
            $v = (float)$r->avg_nilai;
            $avgWeighted[$r->karyawan_id][$r->sub_kriteria_id] = $v * (float)$wSub[$r->sub_kriteria_id];
        }

        $roundedW = [];
        foreach ($karyawans as $kar) {
            $roundedW[$kar->id] = [];
            foreach ($subs as $s) {
                $v = $avgWeighted[$kar->id][$s->id];
                $roundedW[$kar->id][$s->id] = is_null($v) ? null : round($v*100,0,PHP_ROUND_HALF_UP)/100;
            }
        }

        // Max/Min per sub dari avgWeighted
         $max = []; $min = [];
        foreach ($subs as $sub) {
            $vals = [];
            foreach ($karyawans as $kar) {
                $v = $roundedW[$kar->id][$sub->id];
                if ($v !== null) $vals[] = $v;
            }
            $max[$sub->id] = $vals ? max($vals) : 0.0;
            $min[$sub->id] = $vals ? min($vals) : 0.0;
        }

        // Normalisasi SAW (pakai roundedW)
        $norm = [];
        foreach ($karyawans as $kar) {
            $norm[$kar->id] = [];
            foreach ($subs as $sub) {
                $v = $roundedW[$kar->id][$sub->id];
                if ($v === null) { $norm[$kar->id][$sub->id] = null; continue; }

                if ($sub->tipe === 'benefit') {
                    $den = $max[$sub->id];
                    $norm[$kar->id][$sub->id] = $den > 0 ? $v / $den : null;
                } else { // cost
                    $norm[$kar->id][$sub->id] = ($v > 0 && $min[$sub->id] > 0) ? $min[$sub->id] / $v : null;
                }
            }
        }

        $normInt = [];     // 0..100 (int)
        $normInt01 = [];   // 0..1 (sudah dibulatkan 0 desimal %)
        foreach ($karyawans as $kar) {
            $normInt[$kar->id] = [];
            $normInt01[$kar->id] = [];
            foreach ($subs as $s) {
                $nv = $norm[$kar->id][$s->id]; // 0..1 atau null
                if (is_null($nv)) {
                    $normInt[$kar->id][$s->id] = null;
                    $normInt01[$kar->id][$s->id] = null;
                } else {
                    $p = (int) round($nv * 100, 0, PHP_ROUND_HALF_UP);
                    $normInt[$kar->id][$s->id]   = $p;          // 0..100
                    $normInt01[$kar->id][$s->id] = $p / 100.0; // 0..1 (bulat 0 desimal)
                }
            }
        }

        // Fallback bila hanya 1 alternatif → tampilkan avgWeighted agar tidak 100% semua
        if ($karyawans->count() < 2) {
            $norm = $roundedW;
        }

        // Hitung Preferensi V (Σ r_ij * w_kriteria)
        $byKriteria = $subs->groupBy('kriteria_id'); // tiap group punya w_kriteria sama

        $rows = [];
        foreach ($karyawans as $kar) {
            $vTotalPct = 0.0; // V dalam persen (bukan 0..1)

            foreach ($byKriteria as $kid => $subsInK) {
                $wK = (float)$subsInK->first()->w_kriteria; // 0..1

                // Ambil normalisasi per SUB yg sdh dibulatkan 0 desimal (%)
                // lalu rata-ratakan dalam PERSEN (bisa 92.5 dst)
                $sumPct = 0.0; $n = 0;
                foreach ($subsInK as $s) {
                    $p = $normInt[$kar->id][$s->id]; // 0..100 atau null
                    if ($p !== null) { $sumPct += $p; $n++; }
                }
                if ($n > 0) {
                    $avgK_pct = $sumPct / $n;        // contoh: 92.5 (PERSEN)
                    $vTotalPct += $avgK_pct * $wK;   // SUMPRODUCT: rata2% * bobot(0..1)
                }
            }
            $vTotal = $vTotalPct / 100.0; // Ubah ke 0..1
            $rows[] = [
                'id'        => $kar->id,
                'kode'      => $kar->kode,
                'nama'      => $kar->nama,
                'jabatan'   => $kar->jabatan,
                'score'     => $vTotal,                                   // 0..1
                'score_pct' => rtrim(rtrim(number_format($vTotalPct, 3, '.', ''), '0'), '.'),
                'eligible'  => $vTotal >= 0.80,
            ];
        }

        // Filtering
        if ($filter === 'eligible')   $rows = array_values(array_filter($rows, fn($r)=>$r['eligible']));
        if ($filter === 'ineligible') $rows = array_values(array_filter($rows, fn($r)=>!$r['eligible']));

        // Sorting
        if ($filter === 'highest') usort($rows, fn($a,$b)=>$b['score']<=>$a['score']);
        elseif ($filter === 'lowest') usort($rows, fn($a,$b)=>$a['score']<=>$b['score']);
        else usort($rows, fn($a,$b)=>$a['kode']<=>$b['kode']); // default desc

        // Beri ranking
        $rank = 1;
        foreach ($rows as &$r) { $r['rank'] = $rank++; }

    //     dd([
    //     'final_results' => $rows
    // ]);

        return view('hasil.index', [
            'tahun'    => $tahun,
            'jabatan'  => $jabatan,
            'q'        => $q,
            'filter'   => $filter,
            'rows'     => collect($rows),
            'threshold'=> $threshold*100,
            'subs'     => $subs,
        ]);
    }
}
