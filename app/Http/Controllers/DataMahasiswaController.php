<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\MahasiswaProfile;
use App\Models\User;
use Illuminate\Http\Request;

class DataMahasiswaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'mahasiswa') {
            abort(403);
        } elseif ($user->role === 'dosen') {
            $mahasiswas = MahasiswaProfile::with(['user', 'dosen'])
                ->where('dosen_id', $user->id)
                ->latest()
                ->get();
        } else {
            $mahasiswas = MahasiswaProfile::with(['user', 'dosen'])
                ->latest()
                ->get();
        }

        $akunMahasiswa = User::where('role', 'mahasiswa')->orderBy('name')->get();
        $akunDosen = User::where('role', 'dosen')->orderBy('name')->get();

        return view('data-mahasiswa.index', compact('mahasiswas', 'akunMahasiswa', 'akunDosen', 'user'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'dosen_id' => 'nullable|exists:users,id',
            'nim' => 'nullable|string|max:50',
            'nama_lengkap' => 'nullable|string|max:255',
            'prodi' => 'nullable|string|max:255',
            'kelas' => 'nullable|string|max:100',
            'semester' => 'nullable|integer|min:1',
            'no_hp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
        ]);

        MahasiswaProfile::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'dosen_id' => $request->dosen_id,
                'nim' => $request->nim,
                'nama_lengkap' => $request->nama_lengkap,
                'prodi' => $request->prodi,
                'kelas' => $request->kelas,
                'semester' => $request->semester,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]
        );

        if ($request->dosen_id) {
    Conversation::firstOrCreate(
        [
            'dosen_id' => $request->dosen_id,
            'mahasiswa_id' => $request->user_id,
        ],
        [
            'judul' => 'Bimbingan Akademik',
            'status' => 'aktif',
        ]
    );
}

        return back()->with('success', 'Data mahasiswa berhasil disimpan.');
    }

    public function destroy(MahasiswaProfile $mahasiswa)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $mahasiswa->delete();

        return back()->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}