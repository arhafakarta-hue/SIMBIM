@extends('layouts.app', ['title' => 'Register SIMBIM'])

@section('content')
<div class="auth-page">
    <div class="auth-shell">
        <section class="auth-hero">
            <div class="auth-hero-content">
                <span class="auth-tag">Registrasi Terverifikasi</span>
                <div>
                    <h1>Daftar memakai identitas kampus.</h1>
                    <p>Role akun akan ditentukan otomatis dari NIM, NIDN/NIP, atau kode admin yang sudah dimasukkan oleh admin.</p>
                </div>
                <div class="hero-decoration"></div>
            </div>
        </section>

        <section class="auth-card">
            <div class="logo-text">
                <div class="logo">SB</div>
                <div>
                    <div class="logo-title">SIMBIM</div>
                    <div class="logo-subtitle">Registrasi Akun</div>
                </div>
            </div>

            <h1>Buat Akun</h1>
            <p class="muted">Masukkan data diri sesuai identitas yang sudah terdaftar di sistem.</p>

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="grid">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                </div>
                <div class="form-group">
                    <label>Nomor Identitas</label>
                    <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas') }}" placeholder="NIM / NIDN / Kode Admin" required>
                </div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
                    </div>
                </div>
                <button type="submit">Daftar Sekarang</button>
            </form>

            <p class="muted" style="margin-top: 18px; text-align:center;">
                Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--primary); font-weight:900;">Login</a>
            </p>
        </section>
    </div>
</div>
@endsection
