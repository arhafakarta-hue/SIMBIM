<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('progress_studis', function (Blueprint $table) {
            if (!Schema::hasColumn('progress_studis', 'mahasiswa_id')) {
                $table->unsignedBigInteger('mahasiswa_id')->nullable();
            }

            if (!Schema::hasColumn('progress_studis', 'semester')) {
                $table->integer('semester')->nullable();
            }

            if (!Schema::hasColumn('progress_studis', 'ip')) {
                $table->decimal('ip', 3, 2)->nullable();
            }

            if (!Schema::hasColumn('progress_studis', 'ipk')) {
                $table->decimal('ipk', 3, 2)->nullable();
            }

            if (!Schema::hasColumn('progress_studis', 'sks_lulus')) {
                $table->integer('sks_lulus')->nullable();
            }

            if (!Schema::hasColumn('progress_studis', 'file_khs')) {
                $table->string('file_khs')->nullable();
            }

            if (!Schema::hasColumn('progress_studis', 'catatan_mahasiswa')) {
                $table->text('catatan_mahasiswa')->nullable();
            }

            if (!Schema::hasColumn('progress_studis', 'catatan_dosen')) {
                $table->text('catatan_dosen')->nullable();
            }

            if (!Schema::hasColumn('progress_studis', 'status')) {
                $table->string('status')->default('menunggu_review');
            }
        });
    }

    public function down(): void
    {
        Schema::table('progress_studis', function (Blueprint $table) {
            //
        });
    }
};