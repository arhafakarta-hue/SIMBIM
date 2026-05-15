<?php

namespace App\Http\Controllers;

use App\Models\JadwalBimbingan;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalBimbinganController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'mahasiswa') {
            $jadwals = JadwalBimbingan::with(['mahasiswa', 'dosen'])
                ->where('mahasiswa_id', $user->id)
                ->latest()
                ->get();

            $dosens = User::where('role', 'dosen')->orderBy('name')->get();
        } elseif ($user->role === 'dosen') {
            $jadwals = JadwalBimbingan::with(['mahasiswa', 'dosen'])
                ->where('dosen_id', $user->id)
                ->latest()
                ->get();

            $dosens = collect();
        } else {
            $jadwals = JadwalBimbingan::with(['mahasiswa', 'dosen'])
                ->latest()
                ->get();

            $dosens = User::where('role', 'dosen')->orderBy('name')->get();
        }

        return view('jadwal.index', compact('jadwals', 'dosens', 'user'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'mahasiswa') {
            abort(403);
        }

        $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam' => 'required',
            'topik' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        JadwalBimbingan::create([
            'mahasiswa_id' => auth()->id(),
            'dosen_id' => $request->dosen_id,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'topik' => $request->topik,
            'keterangan' => $request->keterangan,
            'status' => 'menunggu',
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal bimbingan berhasil diajukan.');
    }

    public function approve(Request $request, JadwalBimbingan $jadwal)
    {
        if (auth()->user()->role !== 'dosen' || $jadwal->dosen_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'catatan_dosen' => 'nullable|string',
        ]);

        $jadwal->update([
            'status' => 'disetujui',
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        return back()->with('success', 'Jadwal berhasil disetujui.');
    }

    public function reject(Request $request, JadwalBimbingan $jadwal)
    {
        if (auth()->user()->role !== 'dosen' || $jadwal->dosen_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'catatan_dosen' => 'nullable|string',
        ]);

        $jadwal->update([
            'status' => 'ditolak',
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        return back()->with('success', 'Jadwal berhasil ditolak.');
    }

    public function finish(JadwalBimbingan $jadwal)
    {
        if (auth()->user()->role !== 'dosen' || $jadwal->dosen_id !== auth()->id()) {
            abort(403);
        }

        $jadwal->update([
            'status' => 'selesai',
        ]);

        return back()->with('success', 'Jadwal ditandai selesai.');
    }
}