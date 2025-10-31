<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiNilai extends Model
{
    protected $table = 'kpi_nilais';
    protected $fillable = ['karyawan_id','sub_kriteria_id','tahun','bulan','nilai'];

    public function karyawan() { return $this->belongsTo(Karyawan::class); }
    public function subKriteria() { return $this->belongsTo(SubKriteria::class); }
}
