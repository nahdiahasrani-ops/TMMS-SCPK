<?php

// app/Imports/TmmsMasterImport.php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Collection;

class TmmsMasterImport implements WithMultipleSheets
{
    public KaryawanImport $impKaryawan;
    public KpiNilaiImport $impKpi;

    public function __construct(
        bool $updateKaryawan = true,
        bool $replaceYear = false,
        bool $clearEmpty = false
    ) {
        $this->impKaryawan = new KaryawanImport(updateIfExists: $updateKaryawan);
        $this->impKpi      = new KpiNilaiImport(replaceYear: $replaceYear, clearEmpty: $clearEmpty);
    }

    /** @return array<string,\Maatwebsite\Excel\Concerns\ToCollection> */
    public function sheets(): array
    {
        // Key = nama sheet yang diharapkan di file
        return [
            'Karyawan' => $this->impKaryawan,
            'KPI'      => $this->impKpi,
        ];
    }

    /** Gabungkan kegagalan dari semua sheet */
    public function failures(): Collection
    {
        return collect()
            ->merge($this->tagFailures($this->impKaryawan->failures(), 'Karyawan'))
            ->merge($this->tagFailures($this->impKpi->failures(), 'KPI'));
    }

    protected function tagFailures($fails, string $sheet)
    {
        return collect($fails ?? [])->map(function ($f) use ($sheet) {
            // sisipkan nama sheet agar mudah dibaca di UI
            $f->sheetName = $sheet;
            return $f;
        });
    }
}
