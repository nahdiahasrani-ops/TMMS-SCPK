<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiNilai extends Model
{
    use HasFactory;
    protected $table = 'kpi_nilais';

    protected $fillable = [
        'data_karyawans_id',
        'kriteria_id',
        'bulan',
        'tahun',
        'nilai',
    ];

    public function karyawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'data_karyawan_id');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

}
