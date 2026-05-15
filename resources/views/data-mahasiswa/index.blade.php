@extends('layouts.app', ['title' => 'Data Mahasiswa'])

@section('content')
<div class="container grid">
    <div class="card">
        <h1>Data Mahasiswa</h1>
        <p class="muted">Halaman ini digunakan untuk mengelola dan melihat data mahasiswa bimbingan.</p>

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

    @if($user->role === 'admin')
        <div class="card">
            <h2>Tambah / Update Data Mahasiswa</h2>

            <form action="{{ route('data-mahasiswa.store') }}" method="POST" class="grid">
                @csrf

                <div class="form-group">
                    <label>Akun Mahasiswa</label>
                    <select name="user_id" required>
                        <option value="">Pilih akun mahasiswa</option>
                        @foreach($akunMahasiswa as $akun)
                            <option value="{{ $akun->id }}">{{ $akun->name }} - {{ $akun->email }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Dosen Wali</label>
                    <select name="dosen_id">
                        <option value="">Pilih dosen wali</option>
                        @foreach($akunDosen as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->name }} - {{ $dosen->email }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" name="nim" placeholder="Contoh: 22010001">
                    </div>

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" placeholder="Nama lengkap mahasiswa">
                    </div>
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
                        <label>No. HP</label>
                        <input type="text" name="no_hp" placeholder="Contoh: 08123456789">
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" placeholder="Alamat mahasiswa"></textarea>
                </div>

                <button type="submit">Simpan Data Mahasiswa</button>
            </form>
        </div>
    @endif

    <div class="card">
        <h2>Daftar Mahasiswa</h2>

        @forelse($mahasiswas as $mahasiswa)
            <div class="info-card" style="margin-bottom: 16px;">
                <h3>{{ $mahasiswa->nama_lengkap ?? $mahasiswa->user->name ?? '-' }}</h3>

                <p>
                    <b>Akun:</b> {{ $mahasiswa->user->email ?? '-' }} <br>
                    <b>NIM:</b> {{ $mahasiswa->nim ?? '-' }} <br>
                    <b>Program Studi:</b> {{ $mahasiswa->prodi ?? '-' }} <br>
                    <b>Kelas:</b> {{ $mahasiswa->kelas ?? '-' }} <br>
                    <b>Semester:</b> {{ $mahasiswa->semester ?? '-' }} <br>
                    <b>Dosen Wali:</b> {{ $mahasiswa->dosen->name ?? '-' }} <br>
                    <b>No. HP:</b> {{ $mahasiswa->no_hp ?? '-' }} <br>
                    <b>Alamat:</b> {{ $mahasiswa->alamat ?? '-' }}
                </p>

                @if($user->role === 'admin')
                    <form action="{{ route('data-mahasiswa.destroy', $mahasiswa) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data mahasiswa ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn-danger" type="submit">Hapus</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="muted">Belum ada data mahasiswa.</p>
        @endforelse
    </div>
</div>
@endsection