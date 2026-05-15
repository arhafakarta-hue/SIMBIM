<?php

use App\Http\Controllers\IdentitasRegistrasiController;
use App\Http\Controllers\RiwayatBimbinganController;
use App\Http\Controllers\RekomendasiMataKuliahController;
use App\Http\Controllers\ProgressStudiController;
use App\Http\Controllers\DataMahasiswaController;
use App\Http\Controllers\DataDosenController;
use App\Http\Controllers\JadwalBimbinganController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/data-mahasiswa', [DataMahasiswaController::class, 'index'])->name('data-mahasiswa.index');
    Route::post('/data-mahasiswa', [DataMahasiswaController::class, 'store'])->name('data-mahasiswa.store');
    Route::delete('/data-mahasiswa/{mahasiswa}', [DataMahasiswaController::class, 'destroy'])->name('data-mahasiswa.destroy');
    Route::get('/progress-studi', [ProgressStudiController::class, 'index'])->name('progress-studi.index');
    Route::post('/progress-studi', [ProgressStudiController::class, 'store'])->name('progress-studi.store');
    Route::patch('/progress-studi/{progress}/review', [ProgressStudiController::class, 'review'])->name('progress-studi.review');
    Route::delete('/progress-studi/{progress}', [ProgressStudiController::class, 'destroy'])->name('progress-studi.destroy');
    Route::get('/rekomendasi-matkul', [RekomendasiMataKuliahController::class, 'index'])->name('rekomendasi-matkul.index');
    Route::post('/rekomendasi-matkul', [RekomendasiMataKuliahController::class, 'store'])->name('rekomendasi-matkul.store');
    Route::delete('/rekomendasi-matkul/{rekomendasi}', [RekomendasiMataKuliahController::class, 'destroy'])->name('rekomendasi-matkul.destroy');
    Route::get('/riwayat-bimbingan', [RiwayatBimbinganController::class, 'index'])->name('riwayat-bimbingan.index');
    Route::get('/identitas-registrasi', [IdentitasRegistrasiController::class, 'index'])->name('identitas-registrasi.index');
    Route::post('/identitas-registrasi', [IdentitasRegistrasiController::class, 'store'])->name('identitas-registrasi.store');
    Route::delete('/identitas-registrasi/{identitas}', [IdentitasRegistrasiController::class, 'destroy'])->name('identitas-registrasi.destroy');
    Route::patch('/identitas-registrasi/{identitas}/reset', [IdentitasRegistrasiController::class, 'reset'])->name('identitas-registrasi.reset');
    
    Route::get('/data-dosen', [DataDosenController::class, 'index'])->name('data-dosen.index');
    Route::post('/data-dosen', [DataDosenController::class, 'store'])->name('data-dosen.store');
    Route::delete('/data-dosen/{dosen}', [DataDosenController::class, 'destroy'])->name('data-dosen.destroy');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/messages', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{conversation}/messages', [ChatController::class, 'messages'])->name('chat.messages');

    Route::get('/jadwal-bimbingan', [JadwalBimbinganController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal-bimbingan', [JadwalBimbinganController::class, 'store'])->name('jadwal.store');
    Route::patch('/jadwal-bimbingan/{jadwal}/approve', [JadwalBimbinganController::class, 'approve'])->name('jadwal.approve');
    Route::patch('/jadwal-bimbingan/{jadwal}/reject', [JadwalBimbinganController::class, 'reject'])->name('jadwal.reject');
    Route::patch('/jadwal-bimbingan/{jadwal}/finish', [JadwalBimbinganController::class, 'finish'])->name('jadwal.finish');
});