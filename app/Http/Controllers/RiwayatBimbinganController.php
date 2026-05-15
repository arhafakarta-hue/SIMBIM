<?php

namespace App\Http\Controllers;

use App\Models\JadwalBimbingan;

class RiwayatBimbinganController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = JadwalBimbingan::with(['mahasiswa', 'dosen'])
            ->whereIn('status', ['disetujui', 'ditolak', 'selesai'])
            ->latest();

        if ($user->role === 'mahasiswa') {
            $query->where('mahasiswa_id', $user->id);
        }

        if ($user->role === 'dosen') {
            $query->where('dosen_id', $user->id);
        }

        $riwayats = $query->get();

        return view('riwayat-bimbingan.index', compact('riwayats', 'user'));
    }
}