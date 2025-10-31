<?php

// app/Http/Controllers/BulkImportController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TmmsMasterImport;
use App\Imports\KaryawanImport;
use App\Imports\KpiNilaiImport;
use Throwable;

class BulkImportController extends Controller
{
    public function importKaryawan(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $import = new KaryawanImport(updateIfExists: true);

        try { Excel::import($import, $request->file('file')); }
        catch (Throwable $e) {
            return back()->withErrors(['import_karyawan' => 'Gagal membaca file: '.$e->getMessage()]);
        }
        if ($import->failures()->isNotEmpty()) {
            return back()->withErrors($import->failures()->map(fn($f) => "Baris {$f->row()}: ".implode('; ', $f->errors()))->toArray());
        }
        return back()->with('success','Import karyawan selesai.');
    }

    public function importKpi(Request $request)
    {
        $data = $request->validate([
            'file'          => ['required','file','mimes:xlsx,csv,txt'],
            'replace_year'  => ['nullable','boolean'], // hapus nilai lama tahun tsb sebelum import
            'clear_empty'   => ['nullable','boolean'], // kosongkan cell => hapus existing
        ]);

        $import = new KpiNilaiImport(
            replaceYear: (bool)($data['replace_year'] ?? false),
            clearEmpty:  (bool)($data['clear_empty'] ?? false),
        );

        try { Excel::import($import, $request->file('file')); }
        catch (Throwable $e) {
            return back()->withErrors(['import_kpi' => 'Gagal membaca file: '.$e->getMessage()]);
        }
        if ($import->failures()->isNotEmpty()) {
            return back()->withErrors($import->failures()->map(fn($f) => "Baris {$f->row()}: ".implode('; ', $f->errors()))->toArray());
        }
        return back()->with('success','Import KPI selesai.');
    }

    public function importMaster(Request $request)
    {
        $data = $request->validate([
            'file'            => ['required','file','mimes:xlsx,csv,txt'],
            'update_karyawan' => ['nullable','boolean'], // upsert master by kode
            'replace_year'    => ['nullable','boolean'], // hapus nilai tahun tsb sebelum import
            'clear_empty'     => ['nullable','boolean'], // sel kosong = hapus nilai existing
        ]);

        $import = new TmmsMasterImport(
            updateKaryawan: (bool)$request->boolean('update_karyawan', true),
            replaceYear:    (bool)$request->boolean('replace_year', false),
            clearEmpty:     (bool)$request->boolean('clear_empty', false),
        );

        try {
            Excel::import($import, $data['file']);
        } catch (Throwable $e) {
            return back()->withErrors(['import_master' => 'Gagal membaca file: '.$e->getMessage()]);
        }

       $fails = $import->failures();
        if ($fails->isNotEmpty()) {
            return back()->withErrors(
                $fails->map(function ($f) {
                    $sheet = property_exists($f, 'sheetName') ? $f->sheetName : 'Sheet';
                    return 'Baris ' . $f->row() . ' (' . $f->attribute() . ' @ ' . $sheet . '): ' . implode('; ', $f->errors());
                })->toArray()
            );
        }


        return back()->with('success','Import Karyawan & KPI Berhasil.');
    }
}

