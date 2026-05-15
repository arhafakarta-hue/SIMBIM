<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul')->default('Bimbingan Akademik');
            $table->string('status')->default('aktif');
            $table->text('rekomendasi_mata_kuliah')->nullable();
            $table->text('catatan_dosen')->nullable();
            $table->timestamps();

            $table->unique(['dosen_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
