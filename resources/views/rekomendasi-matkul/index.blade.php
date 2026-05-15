@extends('layouts.app', ['title' => 'Rekomendasi Mata Kuliah'])

@section('content')
<div class="container grid">
    <div class="card">
        <h1>Rekomendasi Mata Kuliah</h1>
        <p class="muted">Halaman ini digunakan dosen wali untuk memberikan rekomendasi mata kuliah kepada mahasiswa bimbingan.</p>

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

    @if(in_array($user->role, ['dosen', 'admin']))
        <div class="card">
            <h2>Tambah Rekomendasi</h2>

            <form action="{{ route('rekomendasi-matkul.store') }}" method="POST" class="grid">
                @csrf

                <div class="form-group">
                    <label>Mahasiswa</label>
                    <select name="mahasiswa_id" required>
                        <option value="">Pilih mahasiswa</option>
                        @foreach($mahasiswas as $mahasiswa)
                            <option value="{{ $mahasiswa->id }}">{{ $mahasiswa->name }} - {{ $mahasiswa->email }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>Kode Mata Kuliah</label>
                        <input type="text" name="kode_mata_kuliah" placeholder="Contoh: SI204">
                    </div>

                    <div class="form-group">
                        <label>Nama Mata Kuliah</label>
                        <input type="text" name="nama_mata_kuliah" placeholder="Contoh: Analisis dan Perancangan Sistem" required>
                    </div>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>SKS</label>
                        <input type="number" name="sks" min="1" max="6" placeholder="Contoh: 3">
                    </div>

                    <div class="form-group">
                        <label>Semester Rekomendasi</label>
                        <input type="number" name="semester_rekomendasi" min="1" placeholder="Contoh: 5">
                    </div>
                </div>

                <div class="form-group">
                    <label>Status Rekomendasi</label>
                    <select name="status" required>
                        <option value="direkomendasikan">Direkomendasikan</option>
                        <option value="opsional">Opsional</option>
                        <option value="tidak_direkomendasikan">Tidak Direkomendasikan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Alasan Rekomendasi</label>
                    <textarea name="alasan_rekomendasi" placeholder="Contoh: Mata kuliah ini sesuai dengan progress akademik mahasiswa dan dapat diambil semester depan."></textarea>
                </div>

                <div class="form-group">
                    <label>Catatan Tambahan</label>
                    <textarea name="catatan_tambahan" placeholder="Catatan tambahan jika ada..."></textarea>
                </div>

                <button type="submit">Simpan Rekomendasi</button>
            </form>
        </div>
    @endif

    <div class="card">
        <h2>Daftar Rekomendasi Mata Kuliah</h2>

        @forelse($rekomendasis as $rekomendasi)
            <div class="info-card" style="margin-bottom: 16px;">
                <h3>{{ $rekomendasi->nama_mata_kuliah }}</h3>

                <p>
                    <b>Kode:</b> {{ $rekomendasi->kode_mata_kuliah ?? '-' }} <br>
                    <b>SKS:</b> {{ $rekomendasi->sks ?? '-' }} <br>
                    <b>Semester Rekomendasi:</b> {{ $rekomendasi->semester_rekomendasi ?? '-' }} <br>
                    <b>Status:</b> {{ strtoupper(str_replace('_', ' ', $rekomendasi->status)) }} <br>
                    <b>Mahasiswa:</b> {{ $rekomendasi->mahasiswa->name ?? '-' }} <br>
                    <b>Dosen:</b> {{ $rekomendasi->dosen->name ?? '-' }}
                </p>

                @if($rekomendasi->alasan_rekomendasi)
                    <p>
                        <b>Alasan Rekomendasi:</b><br>
                        {{ $rekomendasi->alasan_rekomendasi }}
                    </p>
                @endif

                @if($rekomendasi->catatan_tambahan)
                    <p>
                        <b>Catatan Tambahan:</b><br>
                        {{ $rekomendasi->catatan_tambahan }}
                    </p>
                @endif

                @if(in_array($user->role, ['dosen', 'admin']))
                    <form action="{{ route('rekomendasi-matkul.destroy', $rekomendasi) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rekomendasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn-danger" type="submit">Hapus Rekomendasi</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="muted">Belum ada rekomendasi mata kuliah.</p>
        @endforelse
    </div>
</div>
@endsection