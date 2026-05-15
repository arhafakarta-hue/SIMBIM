@extends('layouts.app', ['title' => 'Identitas Registrasi'])

@section('content')
<div class="container grid">
    <div class="card">
        <h1>Identitas Registrasi</h1>
        <p class="muted">
            Halaman ini digunakan admin untuk menambahkan NIM mahasiswa, NIDN/NIP dosen, atau kode admin yang boleh digunakan saat registrasi akun.
        </p>

        @if(session('success'))
            <div class="info-card" style="color: green;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="info-card" style="color: red;">
                {{ $errors->first() }}
            </div>
        @endif
    </div>

    <div class="card">
        <h2>Tambah Identitas Baru</h2>

        <form action="{{ route('identitas-registrasi.store') }}" method="POST" class="grid">
            @csrf

            <div class="form-group">
                <label>Nomor Identitas</label>
                <input type="text" name="nomor_identitas" placeholder="Contoh: 22010003 / 1987654323 / ADMIN-02" required>
            </div>

            <div class="form-group">
                <label>Nama Pemilik Identitas</label>
                <input type="text" name="nama" placeholder="Masukkan nama mahasiswa/dosen/admin" required>
            </div>

            <div class="form-group">
                <label>Jenis Akun</label>
                <select name="role" required>
                    <option value="">Pilih jenis akun</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="dosen">Dosen Wali</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label>Program Studi</label>
                    <input type="text" name="prodi" placeholder="Contoh: Sistem Informasi">
                </div>

                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" name="kelas" placeholder="Contoh: SI-4A">
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label>Semester</label>
                    <input type="number" name="semester" min="1" placeholder="Contoh: 4">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <button type="submit">Simpan Identitas</button>
        </form>
    </div>

    <div class="card">
        <h2>Daftar Identitas Registrasi</h2>

        @forelse($identitas as $item)
            <div class="info-card" style="margin-bottom: 16px;">
                <h3>{{ $item->nama }}</h3>

                <p>
                    <b>Nomor Identitas:</b> {{ $item->nomor_identitas }} <br>
                    <b>Jenis Akun:</b> {{ strtoupper($item->role) }} <br>
                    <b>Program Studi:</b> {{ $item->prodi ?? '-' }} <br>
                    <b>Kelas:</b> {{ $item->kelas ?? '-' }} <br>
                    <b>Semester:</b> {{ $item->semester ?? '-' }} <br>
                    <b>Status:</b> {{ strtoupper($item->status) }} <br>
                    <b>Sudah Digunakan:</b> {{ $item->sudah_digunakan ? 'YA' : 'BELUM' }}
                </p>

                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    @if($item->sudah_digunakan)
                        <form action="{{ route('identitas-registrasi.reset', $item) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Reset Penggunaan</button>
                        </form>
                    @endif

                    <form action="{{ route('identitas-registrasi.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus identitas ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn-danger" type="submit">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="muted">Belum ada identitas registrasi.</p>
        @endforelse
    </div>
</div>
@endsection