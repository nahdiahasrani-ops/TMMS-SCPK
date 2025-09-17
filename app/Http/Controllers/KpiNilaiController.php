<?php

namespace App\Http\Controllers;

use App\Models\KpiNilai;
use App\Models\DataKaryawan;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KpiNilaiController extends Controller
{
    public function index($id)
    {
        // Ambil data karyawan berdasarkan ID
        $karyawan = DataKaryawan::findOrFail($id);

        // Ambil semua kriteria yang sesuai dengan jabatan karyawan
        $kriterias = Kriteria::where('role', strtolower($karyawan->role))->get();

        // Ambil semua data KPI berdasarkan karyawan
        $kpiNilais = KpiNilai::where('data_karyawans_id', $id)->get();

        return view('DataKaryawan.KPI', compact('karyawan', 'kriterias', 'kpiNilais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_karyawans_id' => 'required|exists:data_karyawans,id',
            'tanggal' => 'required|date',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
        ]);
        
        $tanggal = \Carbon\Carbon::parse($request->tanggal);
        $bulan = $tanggal->month;
        $tahun = $tanggal->year;
        
        foreach ($request->nilai as $kriteriaId => $nilai) {
            KpiNilai::updateOrCreate(
                [
                    'data_karyawans_id' => $request->data_karyawans_id,
                    'kriteria_id' => $kriteriaId,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ],
                [
                    'nilai' => $nilai ?? 0,
                ]
            );
        }
                
        return redirect()->back()->with('success', 'Data KPI berhasil disimpan!');
    }

}
