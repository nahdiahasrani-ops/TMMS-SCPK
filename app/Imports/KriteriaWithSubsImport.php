<?php

namespace App\Imports;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;

class KriteriaWithSubsImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function __construct(
        protected bool $replaceSubs = true,
        protected bool $updateIfExists = true
    ) {}

    public function rules(): array
    {
        return [
            '*.kode_kriteria'           => ['required','max:10','regex:/^[A-Za-z0-9]+$/'],
            '*.nama_kriteria'           => ['required','max:100'],
            '*.jabatan'                 => ['nullable', Rule::in(['Operator','Mekanik','HSSE'])],
            '*.tipe'                    => ['required', Rule::in(['benefit','cost'])],
            '*.bobot_kriteria_percent'  => ['required','numeric','between:0,100'],

            '*.kode_sub'                => ['nullable','max:20'],
            '*.nama_sub_kriteria'       => ['required','max:150'],
            '*.bobot_sub_percent'       => ['required','numeric','between:0,100'],
        ];
    }

    public function collection(Collection $rows)
    {
        // group menurut kode_kriteria (case-insensitive)
        $grouped = $rows->groupBy(function($r){
            return strtoupper(trim((string)$r['kode_kriteria']));
        });

        foreach ($grouped as $kode => $rowsOfKriteria) {
            // ambil meta kriteria dari baris pertama
            $first = $rowsOfKriteria->first();
            $nama   = trim((string)$first['nama_kriteria']);
            $jab    = $first['jabatan'] !== null ? trim((string)$first['jabatan']) : null;
            $tipe   = strtolower(trim((string)$first['tipe']));
            $bobotK = (float)$first['bobot_kriteria_percent'];

            // cek konsistensi meta di group
            foreach ($rowsOfKriteria as $r) {
                if (strtolower((string)$r['tipe']) !== $tipe) {
                    $this->failures()->push(new \Maatwebsite\Excel\Validators\Failure(
                        $r->get('row') ?? 0, 'tipe', ["Tipe dalam satu kriteria ($kode) harus konsisten."]
                    ));
                    continue 2;
                }
                if (($r['jabatan'] ?? null) !== $jab) {
                    // boleh beda jika salah satunya null, tapi kita biarkan konsisten agar rapi
                }
            }

            // cek total sub = bobot kriteria (dalam persen)
            $sumSub = $rowsOfKriteria->sum(function($r){
                return (float)$r['bobot_sub_percent'];
            });
            if (abs($sumSub - $bobotK) > 0.01) {
                // catat kegagalan kelompok
                $this->failures()->push(new \Maatwebsite\Excel\Validators\Failure(
                    $rowsOfKriteria->first()->get('row') ?? 0,
                    'bobot_sub_percent',
                    ["Total sub untuk {$kode} harus = {$bobotK}% (sekarang {$sumSub}%)."]
                ));
                continue;
            }

            DB::transaction(function () use ($kode, $nama, $jab, $tipe, $bobotK, $rowsOfKriteria) {
                // upsert kriteria
                $payloadK = [
                    'nama_kriteria' => $nama,
                    'jabatan'       => $jab,
                    'tipe'          => $tipe,
                    'bobot'         => $bobotK / 100.0, // simpan desimal
                ];

                $kriteria = Kriteria::where('kode_kriteria', $kode)->first();
                if ($kriteria) {
                    if ($this->updateIfExists) {
                        $kriteria->update($payloadK);
                    }
                } else {
                    $kriteria = Kriteria::create(array_merge(['kode_kriteria'=>$kode], $payloadK));
                }

                // handle sub
                if ($this->replaceSubs) {
                    $kriteria->subKriteria()->delete();
                }

                $counter = 1;
                foreach ($rowsOfKriteria as $r) {
                    $kodeSub = trim((string)($r['kode_sub'] ?? ''));
                    if ($kodeSub === '') {
                        $kodeSub = $kode.'.'.$counter;
                    }
                    $payloadS = [
                        'kriteria_id'       => $kriteria->id,
                        'kode_sub'          => $kodeSub,
                        'nama_sub_kriteria' => trim((string)$r['nama_sub_kriteria']),
                        'bobot'             => ((float)$r['bobot_sub_percent']) / 100.0,
                    ];

                    // upsert sub per (kriteria_id, kode_sub)
                    $existing = $kriteria->subKriteria()->where('kode_sub', $kodeSub)->first();
                    if ($existing) {
                        $existing->update($payloadS);
                    } else {
                        SubKriteria::create($payloadS);
                    }
                    $counter++;
                }
            });
        }
    }
}
