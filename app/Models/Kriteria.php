<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;
    protected $table = 'kriteria';
    Protected $fillable = ['nama_kriteria','bobot','tipe','role'];
    public function kpiNilai()
{
    return $this->hasMany(KpiNilai::class);
}

}

