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
        Schema::create('progress_studis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mahasiswa_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->integer('semester')->nullable();
            $table->decimal('ip', 3, 2)->nullable();
            $table->decimal('ipk', 3, 2)->nullable();
            $table->integer('sks_lulus')->nullable();

            $table->string('file_khs')->nullable();
            $table->text('catatan_mahasiswa')->nullable();
            $table->text('catatan_dosen')->nullable();

            $table->string('status')->default('menunggu_review');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_studis');
    }
};