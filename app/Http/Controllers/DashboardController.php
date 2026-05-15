<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\IdentitasRegistrasi;
use App\Models\JadwalBimbingan;
use App\Models\Message;
use App\Models\ProgressStudi;
use App\Models\RekomendasiMataKuliah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $conversationQuery = Conversation::query();
        $jadwalQuery = JadwalBimbingan::query();
        $progressQuery = ProgressStudi::query();
        $rekomendasiQuery = RekomendasiMataKuliah::query();

        if ($user->role === 'dosen') {
            $conversationQuery->where('dosen_id', $user->id);
            $jadwalQuery->where('dosen_id', $user->id);
            $progressQuery->whereIn('mahasiswa_id', function ($query) use ($user) {
                $query->select('user_id')->from('mahasiswa_profiles')->where('dosen_id', $user->id);
            });
            $rekomendasiQuery->where('dosen_id', $user->id);
        } elseif ($user->role === 'mahasiswa') {
            $conversationQuery->where('mahasiswa_id', $user->id);
            $jadwalQuery->where('mahasiswa_id', $user->id);
            $progressQuery->where('mahasiswa_id', $user->id);
            $rekomendasiQuery->where('mahasiswa_id', $user->id);
        }

        return view('dashboard', [
            'user' => $user,
            'totalUsers' => User::count(),
            'totalMahasiswa' => User::where('role', 'mahasiswa')->count(),
            'totalDosen' => User::where('role', 'dosen')->count(),
            'totalConversations' => (clone $conversationQuery)->count(),
            'totalIdentitas' => IdentitasRegistrasi::count(),
            'identitasBelumDipakai' => IdentitasRegistrasi::where('sudah_digunakan', false)->count(),
            'jadwalMenunggu' => (clone $jadwalQuery)->where('status', 'menunggu')->count(),
            'jadwalSelesai' => (clone $jadwalQuery)->where('status', 'selesai')->count(),
            'progressPending' => (clone $progressQuery)->where('status', 'menunggu_review')->count(),
            'totalRekomendasi' => (clone $rekomendasiQuery)->count(),
            'recentJadwals' => (clone $jadwalQuery)->with(['mahasiswa', 'dosen'])->latest()->limit(4)->get(),
            'latestProgress' => (clone $progressQuery)->with('mahasiswa')->latest()->limit(4)->get(),
            'latestRecommendations' => (clone $rekomendasiQuery)->with(['mahasiswa', 'dosen'])->latest()->limit(4)->get(),
            'latestMessages' => Message::with(['sender', 'conversation'])
                ->when($user->role === 'dosen', fn ($q) => $q->whereHas('conversation', fn ($c) => $c->where('dosen_id', $user->id)))
                ->when($user->role === 'mahasiswa', fn ($q) => $q->whereHas('conversation', fn ($c) => $c->where('mahasiswa_id', $user->id)))
                ->when($user->role === 'admin', fn ($q) => $q->whereRaw('1 = 0'))
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
