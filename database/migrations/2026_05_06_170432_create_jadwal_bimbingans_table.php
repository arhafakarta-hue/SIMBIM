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
        Schema::create('jadwal_bimbingans', function (Blueprint $table) {
            $table->id();

            // user dengan role mahasiswa
            $table->foreignId('mahasiswa_id')
                ->constrained('users')
                ->onDelete('cascade');

            // user dengan role dosen
            $table->foreignId('dosen_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->date('tanggal');
            $table->time('jam');
            $table->string('topik');
            $table->text('keterangan')->nullable();

            // status: menunggu, disetujui, ditolak, selesai
            $table->string('status')->default('menunggu');

            $table->text('catatan_dosen')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_bimbingans');
    }
};