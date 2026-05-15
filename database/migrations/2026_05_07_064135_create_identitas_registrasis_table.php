<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('identitas_registrasis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_identitas')->unique();
            $table->string('nama');
            $table->string('role'); // mahasiswa, dosen, admin
            $table->string('prodi')->nullable();
            $table->string('kelas')->nullable();
            $table->integer('semester')->nullable();
            $table->string('status')->default('aktif');
            $table->boolean('sudah_digunakan')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('identitas_registrasis');
    }
};