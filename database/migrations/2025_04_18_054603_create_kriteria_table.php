<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kriteria', 10)->unique(); // contoh: C1, C2
            $table->string('nama_kriteria', 100);
            $table->decimal('bobot', 5, 4)->default(0);
            $table->enum('jabatan', ['Operator', 'Mekanik', 'HSSE'])
                ->nullable(); // kalau nanti mau fleksibel
            $table->enum('tipe', ['benefit', 'cost'])
                ->default('benefit');
            $table->timestamps();
            $table->index('nama_kriteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
