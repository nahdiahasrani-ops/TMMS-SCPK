<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\KpiNilai;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KpiInputController extends Controller
{
    public function form(Request $r, Karyawan $karyawan)
    {
        $tahun = (int)($r->query('tahun') ?: date('Y'));

        // Ambil Kriteria & Sub sesuai jabatan karyawan
        $kriterias = Kriteria::with(['subKriteria' => function($q){
            $q->orderBy('kode_sub');
        }])->where('jabatan', $karyawan->jabatan)
          ->orderBy('kode_kriteria')->get();

        // Ambil nilai existing per sub/bulan
        $existing = KpiNilai::where('karyawan_id',$karyawan->id)
            ->where('tahun',$tahun)
            ->get()
            ->groupBy('sub_kriteria_id'); // each sub_kriteria_id => collection by months

        return view('karyawan.nilai', compact('karyawan','kriterias','existing','tahun'));
    }

    public function save(Request $r, Karyawan $karyawan)
    {
        $data = $r->validate([
            'tahun' => ['required','integer','between:2000,2100'],
            // nilai[SUB_ID][BULAN] = persen 0..100 atau kosong
            'nilai' => ['required','array'],
        ]);
        $tahun = (int)$data['tahun'];

        DB::transaction(function () use ($karyawan, $tahun, $data) {
            foreach ($data['nilai'] as $subId => $bulanVals) {
                foreach ($bulanVals as $bulan => $pct) {
                    if ($pct === null || $pct === '' ) continue; // skip kosong
                    $pct = (float)$pct;
                    if ($pct < 0) $pct = 0;
                    if ($pct > 100) $pct = 100;

                    KpiNilai::updateOrCreate(
                        [
                            'karyawan_id'     => $karyawan->id,
                            'sub_kriteria_id' => (int)$subId,
                            'tahun'           => $tahun,
                            'bulan'           => (int)$bulan,
                        ],
                        [
                            'nilai'           => $pct / 100.0, // simpan desimal 0..1
                        ]
                    );
                }
            }
        });

        return back()->with('success','Nilai KPI tahun '.$tahun.' tersimpan.');
    }
}
