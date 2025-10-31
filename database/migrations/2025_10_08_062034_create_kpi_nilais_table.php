<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kpi_nilais', function (Blueprint $t) {
            $t->id();
            $t->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $t->foreignId('sub_kriteria_id')->constrained('sub_kriteria')->cascadeOnDelete();
            $t->unsignedSmallInteger('tahun');         // mis. 2024
            $t->unsignedTinyInteger('bulan');          // 1..12
            $t->decimal('nilai', 5, 4)->nullable();    // 0..1 (simpen desimal)
            $t->timestamps();

            $t->unique(['karyawan_id','sub_kriteria_id','tahun','bulan']);
            $t->index(['tahun','bulan']);
        });

    }
    public function down(): void { Schema::dropIfExists('kpi_nilais'); }
};