<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaProfile;
use App\Models\ProgressStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgressStudiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'mahasiswa') {
            $progressStudis = ProgressStudi::with('mahasiswa')
                ->where('mahasiswa_id', $user->id)
                ->latest()
                ->get();
        } elseif ($user->role === 'dosen') {
            $mahasiswaIds = MahasiswaProfile::where('dosen_id', $user->id)
                ->pluck('user_id');

            $query = ProgressStudi::with('mahasiswa')->latest();

            if ($mahasiswaIds->isNotEmpty()) {
                $query->whereIn('mahasiswa_id', $mahasiswaIds);
            } else {
                $query->whereIn('mahasiswa_id', User::where('role', 'mahasiswa')->pluck('id'));
            }

            $progressStudis = $query->get();
        } else {
            $progressStudis = ProgressStudi::with('mahasiswa')
                ->latest()
                ->get();
        }

        return view('progress-studi.index', compact('progressStudis', 'user'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'mahasiswa') {
            abort(403);
        }

        $request->validate([
            'semester' => 'required|integer|min:1',
            'ip' => 'nullable|numeric|min:0|max:4',
            'ipk' => 'nullable|numeric|min:0|max:4',
            'sks_lulus' => 'nullable|integer|min:0',
            'file_khs' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'catatan_mahasiswa' => 'nullable|string',
        ]);

        $filePath = null;

        if ($request->hasFile('file_khs')) {
            $filePath = $request->file('file_khs')->store('khs', 'public');
        }

        ProgressStudi::create([
            'mahasiswa_id' => auth()->id(),
            'semester' => $request->semester,
            'ip' => $request->ip,
            'ipk' => $request->ipk,
            'sks_lulus' => $request->sks_lulus,
            'file_khs' => $filePath,
            'catatan_mahasiswa' => $request->catatan_mahasiswa,
            'status' => 'menunggu_review',
        ]);

        return back()->with('success', 'Progress studi berhasil diupload.');
    }

    public function review(Request $request, ProgressStudi $progress)
    {
        if (!in_array(auth()->user()->role, ['dosen', 'admin'])) {
            abort(403);
        }

        $request->validate([
            'catatan_dosen' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $progress->update([
            'catatan_dosen' => $request->catatan_dosen,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Review progress studi berhasil disimpan.');
    }

    public function destroy(ProgressStudi $progress)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $progress->mahasiswa_id !== $user->id) {
            abort(403);
        }

        if ($progress->file_khs) {
            Storage::disk('public')->delete($progress->file_khs);
        }

        $progress->delete();

        return back()->with('success', 'Progress studi berhasil dihapus.');
    }
}