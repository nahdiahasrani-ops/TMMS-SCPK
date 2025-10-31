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
        Schema::create('sub_kriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id')
                ->constrained('kriteria')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('kode_sub', 20); // contoh: C1.1, C1.2
            $table->string('nama_sub_kriteria', 150);
            $table->decimal('bobot', 5, 4)->default(0);

            $table->timestamps();

            $table->unique(['kriteria_id', 'kode_sub']);
            $table->index(['kriteria_id', 'nama_sub_kriteria']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_kriteria');
    }
};
