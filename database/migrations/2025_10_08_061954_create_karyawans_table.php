<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('karyawans', function (Blueprint $t) {
            $t->id();
            $t->string('kode', 30)->unique();      // NIK/NIP/kode internal
            $t->string('nama', 120);
            $t->enum('jabatan', ['Operator','Mekanik','HSSE']);
            $t->date('tgl_masuk')->nullable();
            $t->enum('status', ['aktif','nonaktif'])->default('aktif');
            $t->timestamps();
            $t->index(['nama','jabatan']);
        });
    }
    public function down(): void { Schema::dropIfExists('karyawans'); }
};
