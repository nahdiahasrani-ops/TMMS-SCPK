<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKaryawan extends Model
{
    use HasFactory;

    protected $table = 'data_karyawans';

    protected $fillable = [
        'nama_karyawan',
        'masa_kerja',
        'role',
    ];
    public function kpiNilai()
    {
        return $this->hasMany(KpiNilai::class);
    }

}

