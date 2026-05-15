<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaProfile;
use App\Models\RekomendasiMataKuliah;
use App\Models\User;
use Illuminate\Http\Request;

class RekomendasiMataKuliahController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'mahasiswa') {
            $rekomendasis = RekomendasiMataKuliah::with(['mahasiswa', 'dosen'])
                ->where('mahasiswa_id', $user->id)
                ->latest()
                ->get();

            $mahasiswas = collect();
        } elseif ($user->role === 'dosen') {
            $mahasiswaIds = MahasiswaProfile::where('dosen_id', $user->id)->pluck('user_id');

            if ($mahasiswaIds->isEmpty()) {
                $mahasiswaIds = User::where('role', 'mahasiswa')->pluck('id');
            }

            $rekomendasis = RekomendasiMataKuliah::with(['mahasiswa', 'dosen'])
                ->where('dosen_id', $user->id)
                ->latest()
                ->get();

            $mahasiswas = User::whereIn('id', $mahasiswaIds)
                ->orderBy('name')
                ->get();
        } else {
            $rekomendasis = RekomendasiMataKuliah::with(['mahasiswa', 'dosen'])
                ->latest()
                ->get();

            $mahasiswas = User::where('role', 'mahasiswa')
                ->orderBy('name')
                ->get();
        }

        return view('rekomendasi-matkul.index', compact('rekomendasis', 'mahasiswas', 'user'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['dosen', 'admin'])) {
            abort(403);
        }

        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
            'kode_mata_kuliah' => 'nullable|string|max:50',
            'nama_mata_kuliah' => 'required|string|max:255',
            'sks' => 'nullable|integer|min:1|max:6',
            'semester_rekomendasi' => 'nullable|integer|min:1',
            'status' => 'required|string',
            'alasan_rekomendasi' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string',
        ]);

        RekomendasiMataKuliah::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'dosen_id' => auth()->id(),
            'kode_mata_kuliah' => $request->kode_mata_kuliah,
            'nama_mata_kuliah' => $request->nama_mata_kuliah,
            'sks' => $request->sks,
            'semester_rekomendasi' => $request->semester_rekomendasi,
            'status' => $request->status,
            'alasan_rekomendasi' => $request->alasan_rekomendasi,
            'catatan_tambahan' => $request->catatan_tambahan,
        ]);

        return back()->with('success', 'Rekomendasi mata kuliah berhasil disimpan.');
    }

    public function destroy(RekomendasiMataKuliah $rekomendasi)
    {
        if (!in_array(auth()->user()->role, ['dosen', 'admin'])) {
            abort(403);
        }

        if (auth()->user()->role === 'dosen' && $rekomendasi->dosen_id !== auth()->id()) {
            abort(403);
        }

        $rekomendasi->delete();

        return back()->with('success', 'Rekomendasi mata kuliah berhasil dihapus.');
    }
}