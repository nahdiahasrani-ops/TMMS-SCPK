<?php

// app/Http/Controllers/AlternatifController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Karyawan;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\KpiNilai;

class AlternatifController extends Controller
{
    public function index(Request $request)
    {
        $tahun   = (int)($request->query('tahun') ?? date('Y'));
        $jabatan = $request->query('jabatan') ?? 'Operator';

        // Ambil semua sub-kriteria utk jabatan tsb + tipe (benefit/cost) + bobot global (jika nanti perlu)
        $subs = SubKriteria::selectRaw('sub_kriteria.*, kriteria.tipe, kriteria.bobot as w_kriteria, (sub_kriteria.bobot * kriteria.bobot) as w_global')
            ->join('kriteria','kriteria.id','=','sub_kriteria.kriteria_id')
            ->where('kriteria.jabatan',$jabatan)
            ->orderBy('kriteria.kode_kriteria')
            ->orderBy('sub_kriteria.kode_sub')
            ->get();

        // Karyawan pada jabatan tsb
        $karyawans = Karyawan::where('jabatan',$jabatan)
            ->orderBy('kode')->orderBy('nama')->get();

        // Avg nilai per karyawan x sub_kriteria (0..1)
        $rows = DB::table('kpi_nilais')
            ->select('karyawan_id','sub_kriteria_id', DB::raw('AVG(nilai) as avg_nilai'))
            ->where('tahun',$tahun)
            ->whereIn('sub_kriteria_id',$subs->pluck('id'))
            ->whereIn('karyawan_id',$karyawans->pluck('id'))
            ->groupBy('karyawan_id','sub_kriteria_id')
            ->get();

        // Bentuk matriks avg: [karyawan_id][sub_id] = avg (0..1) | null jika kosong
        $avg = [];
        foreach ($karyawans as $kar) {
            $avg[$kar->id] = array_fill_keys($subs->pluck('id')->all(), null);
        }
        foreach ($rows as $r) {
            $avg[$r->karyawan_id][$r->sub_kriteria_id] = (float)$r->avg_nilai;
        }

        $avgWeighted = [];
        foreach ($karyawans as $kar) {
            $avgWeighted[$kar->id] = [];
            foreach ($subs as $s) {
                $v = $avg[$kar->id][$s->id];               // 0..1 (avg)
                $avgWeighted[$kar->id][$s->id] = is_null($v)
                    ? null
                    : $v * (float)$s->bobot;                 // bobot sub tersimpan 0..1 (25% => 0.25)
            }
        }

        $roundedW = [];
        foreach ($karyawans as $kar) {
            $roundedW[$kar->id] = [];
            foreach ($subs as $s) {
                $v = $avgWeighted[$kar->id][$s->id]; // 0..1
                $roundedW[$kar->id][$s->id] = is_null($v)
                    ? null
                    : round($v * 100, 0, PHP_ROUND_HALF_UP) / 100; // 0.00..1.00 dengan 0 desimal persen
            }
        }

        // Hitung max & min per sub (untuk normalisasi)
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

        //    dd([
        //         'subs'        => $subs->map(fn($s)=>['id'=>$s->id,'kode'=>$s->kode_sub,'bobot_sub'=>$s->bobot]),
        //         'karyawans'   => $karyawans->map(fn($k)=>['id'=>$k->id,'kode'=>$k->kode,'nama'=>$k->nama]),
        //         'avg'         => $avg,
        //         'avgWeighted' => $avgWeighted,
        //         'roundedW'    => $roundedW,
        //         'max'         => $max,
        //         'min'         => $min,
        //         'norm'        => $norm,
        //     ]);

        // (opsional) siapin bobot global per sub jika nanti mau SAW final
        $wGlobal = $subs->mapWithKeys(fn($s)=>[$s->id => (float)$s->w_global]);

        return view('alternatif.index', [
            'tahun'     => $tahun,
            'jabatan'   => $jabatan,
            'subs'      => $subs,
            'karyawans' => $karyawans,
            'avg'       => $avg,
            'avgWeighted' => $avgWeighted,
            'roundedW'    => $roundedW,
            'norm'      => $norm,
            'max'       => $max,
            'min'       => $min,
            'wGlobal'   => $wGlobal,
        ]);
    }
}
