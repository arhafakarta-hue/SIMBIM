<?php

namespace App\Http\Controllers;

use App\Models\IdentitasRegistrasi;
use Illuminate\Http\Request;

class IdentitasRegistrasiController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $identitas = IdentitasRegistrasi::latest()->get();

        return view('identitas-registrasi.index', compact('identitas'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'nomor_identitas' => 'required|string|unique:identitas_registrasis,nomor_identitas',
            'nama' => 'required|string|max:255',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'prodi' => 'nullable|string|max:255',
            'kelas' => 'nullable|string|max:100',
            'semester' => 'nullable|integer|min:1',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        IdentitasRegistrasi::create([
            'nomor_identitas' => $request->nomor_identitas,
            'nama' => $request->nama,
            'role' => $request->role,
            'prodi' => $request->prodi,
            'kelas' => $request->kelas,
            'semester' => $request->semester,
            'status' => $request->status,
            'sudah_digunakan' => false,
        ]);

        return back()->with('success', 'Identitas registrasi berhasil ditambahkan.');
    }

    public function destroy(IdentitasRegistrasi $identitas)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $identitas->delete();

        return back()->with('success', 'Identitas registrasi berhasil dihapus.');
    }

    public function reset(IdentitasRegistrasi $identitas)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $identitas->update([
            'sudah_digunakan' => false,
        ]);

        return back()->with('success', 'Status penggunaan identitas berhasil direset.');
    }
}