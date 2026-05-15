<?php

namespace App\Http\Controllers;

use App\Models\DosenProfile;
use App\Models\IdentitasRegistrasi;
use App\Models\MahasiswaProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nomor_identitas' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $identitas = IdentitasRegistrasi::where('nomor_identitas', $request->nomor_identitas)
            ->where('status', 'aktif')
            ->first();

        if (! $identitas) {
            return back()->withErrors([
                'nomor_identitas' => 'Nomor identitas tidak terdaftar atau tidak aktif.',
            ])->withInput();
        }

        if ($identitas->sudah_digunakan) {
            return back()->withErrors([
                'nomor_identitas' => 'Nomor identitas ini sudah digunakan untuk registrasi.',
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $identitas->role,
        ]);

        if ($identitas->role === 'mahasiswa') {
            MahasiswaProfile::create([
                'user_id' => $user->id,
                'dosen_id' => null,
                'nim' => $identitas->nomor_identitas,
                'nama_lengkap' => $request->name,
                'prodi' => $identitas->prodi,
                'kelas' => $identitas->kelas,
                'semester' => $identitas->semester,
            ]);
        }

        if ($identitas->role === 'dosen') {
            DosenProfile::create([
                'user_id' => $user->id,
                'nidn' => $identitas->nomor_identitas,
                'nama_lengkap' => $request->name,
                'prodi' => $identitas->prodi,
            ]);
        }

        $identitas->update([
            'sudah_digunakan' => true,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login menggunakan email dan password yang sudah didaftarkan.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
