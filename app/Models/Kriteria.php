<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;
    protected $table = 'kriteria';
    protected $fillable = ['kode_kriteria', 'nama_kriteria', 'bobot', 'jabatan', 'tipe'];

    public function kpiNilai()
        {
            return $this->hasMany(KpiNilai::class);
        }

    public function SubKriteria()
    {
        return $this->hasMany(SubKriteria::class);
    }
}

