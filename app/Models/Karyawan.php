<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $fillable = ['kode','nama','jabatan','tgl_masuk','status'];

    public function nilaiKpi() { return $this->hasMany(KpiNilai::class); }
}