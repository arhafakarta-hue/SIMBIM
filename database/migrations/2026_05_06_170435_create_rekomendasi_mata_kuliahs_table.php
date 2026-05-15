<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekomendasi_mata_kuliahs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mahasiswa_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('dosen_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('kode_mata_kuliah')->nullable();
            $table->string('nama_mata_kuliah');
            $table->integer('sks')->nullable();
            $table->integer('semester_rekomendasi')->nullable();

            $table->string('status')->default('direkomendasikan');
            $table->text('alasan_rekomendasi')->nullable();
            $table->text('catatan_tambahan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasi_mata_kuliahs');
    }
};