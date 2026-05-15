<?php

namespace App\Http\Controllers;

use App\Models\DosenProfile;
use App\Models\User;
use Illuminate\Http\Request;

class DataDosenController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $dosens = DosenProfile::with('user')
            ->latest()
            ->get();

        $akunDosen = User::where('role', 'dosen')->orderBy('name')->get();

        return view('data-dosen.index', compact('dosens', 'akunDosen', 'user'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nidn' => 'nullable|string|max:50',
            'nama_lengkap' => 'nullable|string|max:255',
            'prodi' => 'nullable|string|max:255',
            'bidang_keahlian' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
        ]);

        DosenProfile::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'nidn' => $request->nidn,
                'nama_lengkap' => $request->nama_lengkap,
                'prodi' => $request->prodi,
                'bidang_keahlian' => $request->bidang_keahlian,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]
        );

        return back()->with('success', 'Data dosen wali berhasil disimpan.');
    }

    public function destroy(DosenProfile $dosen)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $dosen->delete();

        return back()->with('success', 'Data dosen wali berhasil dihapus.');
    }
}