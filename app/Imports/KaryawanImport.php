<?php
namespace App\Imports;

use App\Models\Karyawan;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class KaryawanImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function __construct(public bool $updateIfExists = true) {}

    public function rules(): array
    {
        return [
            '*.kode'      => ['required','max:30'],
            '*.nama'      => ['required','max:120'],
            '*.jabatan'   => ['required', Rule::in(['Operator','Mekanik','HSSE','operator','mekanik','hsse'])],
            '*.tgl_masuk' => ['nullable'],
            '*.status'    => ['required', Rule::in(['aktif','nonaktif','Aktif','Nonaktif'])],
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $r) {
            $kode = trim((string)$r['kode']);
            $nama = trim((string)$r['nama']);
            $jab  = ucfirst(strtolower(trim((string)$r['jabatan'])));
            $st   = strtolower(trim((string)$r['status'])) === 'nonaktif' ? 'nonaktif' : 'aktif';

            $tgl = null;
            if (isset($r['tgl_masuk']) && $r['tgl_masuk'] !== '') {
                $raw = $r['tgl_masuk'];
                if (is_numeric($raw)) {
                    $tgl = ExcelDate::excelToDateTimeObject($raw)->format('Y-m-d');
                } else {
                    try { $tgl = Carbon::parse($raw)->format('Y-m-d'); } catch (\Throwable $e) { $tgl = null; }
                }
            }

            $payload = ['nama'=>$nama,'jabatan'=>$jab,'tgl_masuk'=>$tgl,'status'=>$st];
            $model = Karyawan::where('kode',$kode)->first();
            if ($model) {
                if ($this->updateIfExists) $model->update($payload);
            } else {
                Karyawan::create(array_merge(['kode'=>$kode], $payload));
            }
        }
    }
}
