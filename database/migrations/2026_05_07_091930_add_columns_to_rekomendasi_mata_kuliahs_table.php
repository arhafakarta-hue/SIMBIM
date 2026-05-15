<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekomendasi_mata_kuliahs', function (Blueprint $table) {
            if (!Schema::hasColumn('rekomendasi_mata_kuliahs', 'mahasiswa_id')) {
                $table->unsignedBigInteger('mahasiswa_id')->nullable();
            }

            if (!Schema::hasColumn('rekomendasi_mata_kuliahs', 'dosen_id')) {
                $table->unsignedBigInteger('dosen_id')->nullable();
            }

            if (!Schema::hasColumn('rekomendasi_mata_kuliahs', 'kode_mata_kuliah')) {
                $table->string('kode_mata_kuliah')->nullable();
            }

            if (!Schema::hasColumn('rekomendasi_mata_kuliahs', 'nama_mata_kuliah')) {
                $table->string('nama_mata_kuliah')->nullable();
            }

            if (!Schema::hasColumn('rekomendasi_mata_kuliahs', 'sks')) {
                $table->integer('sks')->nullable();
            }

            if (!Schema::hasColumn('rekomendasi_mata_kuliahs', 'semester_rekomendasi')) {
                $table->integer('semester_rekomendasi')->nullable();
            }

            if (!Schema::hasColumn('rekomendasi_mata_kuliahs', 'status')) {
                $table->string('status')->default('direkomendasikan');
            }

            if (!Schema::hasColumn('rekomendasi_mata_kuliahs', 'alasan_rekomendasi')) {
                $table->text('alasan_rekomendasi')->nullable();
            }

            if (!Schema::hasColumn('rekomendasi_mata_kuliahs', 'catatan_tambahan')) {
                $table->text('catatan_tambahan')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('rekomendasi_mata_kuliahs', function (Blueprint $table) {
            //
        });
    }
};