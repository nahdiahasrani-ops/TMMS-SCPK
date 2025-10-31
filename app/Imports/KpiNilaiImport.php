<?php

namespace App\Imports;

use App\Models\Karyawan;
use App\Models\SubKriteria;
use App\Models\KpiNilai;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class KpiNilaiImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function __construct(
        public bool $replaceYear = false,
        public bool $clearEmpty = false
    ) {}

    public function rules(): array
    {
        $monthRules = ['nullable','numeric','between:0,100'];
        return [
            '*.tahun'          => ['required','integer','between:2000,2100'],
            '*.karyawan_kode'  => ['required','max:30'],
            '*.sub_kode'       => ['required','max:20'],
            '*.m1'  => $monthRules, '*.m2'  => $monthRules, '*.m3'  => $monthRules,
            '*.m4'  => $monthRules, '*.m5'  => $monthRules, '*.m6'  => $monthRules,
            '*.m7'  => $monthRules, '*.m8'  => $monthRules, '*.m9'  => $monthRules,
            '*.m10' => $monthRules, '*.m11' => $monthRules, '*.m12' => $monthRules,
        ];
    }

    public function collection(Collection $rows)
    {
        // Group by (karyawan, tahun) kalau replaceYear agar sekali delete
        $toDelete = [];
        if ($this->replaceYear) {
            foreach ($rows as $r) {
                $tahun = (int)$r['tahun'];
                $k = Karyawan::where('kode', trim((string)$r['karyawan_kode']))->first();
                if ($k) $toDelete[$k->id.'|'.$tahun] = [$k->id, $tahun];
            }
            foreach ($toDelete as [$kid,$th]) {
                KpiNilai::where('karyawan_id',$kid)->where('tahun',$th)->delete();
            }
        }

        DB::transaction(function () use ($rows) {
            foreach ($rows as $r) {
                $tahun = (int)$r['tahun'];
                $kodeKar = trim((string)$r['karyawan_kode']);
                $kodeSub = trim((string)$r['sub_kode']);

                $karyawan = Karyawan::where('kode',$kodeKar)->first();
                $sub      = SubKriteria::where('kode_sub',$kodeSub)->first();

                if (!$karyawan || !$sub) { // skip baris yang referensinya tidak ketemu
                    $this->failures()->push(new \Maatwebsite\Excel\Validators\Failure(
                        $r->get('row') ?? 0, 'karyawan_kode/sub_kode',
                        ["Data tidak ditemukan (karyawan: $kodeKar, sub: $kodeSub)."]
                    ));
                    continue;
                }

                for ($m=1; $m<=12; $m++) {
                    $key = 'm'.$m;
                    $val = $r[$key] ?? null;
                    if ($val === '' || $val === null) {
                        if ($this->clearEmpty) {
                            KpiNilai::where([
                                'karyawan_id'=>$karyawan->id,
                                'sub_kriteria_id'=>$sub->id,
                                'tahun'=>$tahun,'bulan'=>$m
                            ])->delete();
                        }
                        continue;
                    }
                    $pct = max(0, min(100, (float)$val));

                    KpiNilai::updateOrCreate(
                        [
                            'karyawan_id'     => $karyawan->id,
                            'sub_kriteria_id' => $sub->id,
                            'tahun'           => $tahun,
                            'bulan'           => $m,
                        ],
                        ['nilai' => $pct / 100.0] // simpan 0..1
                    );
                }
            }
        });
    }
}
