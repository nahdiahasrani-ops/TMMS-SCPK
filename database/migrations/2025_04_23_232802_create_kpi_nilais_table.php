<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kpi_nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_karyawans_id')->constrained('data_karyawans')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->integer('bulan'); // 1-12
            $table->year('tahun');
            $table->integer('nilai'); // misal: 90
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_nilais');
    }
};
