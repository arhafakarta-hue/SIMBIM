@extends('layouts.app', ['title' => 'Data Dosen Wali'])

@section('content')
<div class="container grid">
    <div class="card">
        <h1>Data Dosen Wali</h1>
        <p class="muted">Halaman ini digunakan untuk mengelola dan melihat data dosen wali.</p>

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
            <h2>Tambah / Update Data Dosen Wali</h2>

            <form action="{{ route('data-dosen.store') }}" method="POST" class="grid">
                @csrf

                <div class="form-group">
                    <label>Akun Dosen</label>
                    <select name="user_id" required>
                        <option value="">Pilih akun dosen</option>
                        @foreach($akunDosen as $akun)
                            <option value="{{ $akun->id }}">{{ $akun->name }} - {{ $akun->email }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>NIDN</label>
                        <input type="text" name="nidn" placeholder="Contoh: 1234567890">
                    </div>

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" placeholder="Nama lengkap dosen">
                    </div>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>Program Studi</label>
                        <input type="text" name="prodi" placeholder="Contoh: Sistem Informasi">
                    </div>

                    <div class="form-group">
                        <label>Bidang Keahlian</label>
                        <input type="text" name="bidang_keahlian" placeholder="Contoh: Rekayasa Perangkat Lunak">
                    </div>
                </div>

                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="no_hp" placeholder="Contoh: 08123456789">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" placeholder="Alamat dosen"></textarea>
                </div>

                <button type="submit">Simpan Data Dosen</button>
            </form>
        </div>
    @endif

    <div class="card">
        <h2>Daftar Dosen Wali</h2>

        @forelse($dosens as $dosen)
            <div class="info-card" style="margin-bottom: 16px;">
                <h3>{{ $dosen->nama_lengkap ?? $dosen->user->name ?? '-' }}</h3>

                <p>
                    <b>Akun:</b> {{ $dosen->user->email ?? '-' }} <br>
                    <b>NIDN:</b> {{ $dosen->nidn ?? '-' }} <br>
                    <b>Program Studi:</b> {{ $dosen->prodi ?? '-' }} <br>
                    <b>Bidang Keahlian:</b> {{ $dosen->bidang_keahlian ?? '-' }} <br>
                    <b>No. HP:</b> {{ $dosen->no_hp ?? '-' }} <br>
                    <b>Alamat:</b> {{ $dosen->alamat ?? '-' }}
                </p>

                @if($user->role === 'admin')
                    <form action="{{ route('data-dosen.destroy', $dosen) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data dosen ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn-danger" type="submit">Hapus</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="muted">Belum ada data dosen wali.</p>
        @endforelse
    </div>
</div>
@endsection