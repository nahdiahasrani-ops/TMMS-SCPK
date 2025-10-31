<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KriteriaWithSubsImport;

class KriteriaImportController extends Controller
{
    
   public function import(Request $request)
    {
        $data = $request->validate([
            'file'             => ['required','file','mimes:xlsx,csv,txt'],
            'replace_subs'     => ['nullable','boolean'],
            'update_if_exists' => ['nullable','boolean'],
        ]);

        $import = new KriteriaWithSubsImport(
            (bool)($data['replace_subs'] ?? true),
            (bool)($data['update_if_exists'] ?? true),
        );

        try {
            Excel::import($import, $data['file']);
        } catch (Throwable $e) {
            return back()->withErrors(['file' => 'Gagal membaca file: '.$e->getMessage()], 'import')
                        ->withInput();
        }

        $fails = $import->failures();
        if ($fails->isNotEmpty()) {
            $arr = $fails->map(fn($f) => "Baris {$f->row()}: ".implode('; ', $f->errors()))->toArray();
            return back()->withErrors($arr, 'import')->withInput();
        }

        return back()->with('success', 'Import selesai.');
    }

    public function templates()
    {
        $filePath = storage_path('app/templates/template_import_kriteria_tmms.xlsx');
        return response()->download($filePath, 'template_import_kriteria_tmms.xlsx');
    }
}
