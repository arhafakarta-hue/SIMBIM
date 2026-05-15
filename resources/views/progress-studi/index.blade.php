@extends('layouts.app', ['title' => 'Progress Studi'])

@section('content')
<div class="container grid">
    <div class="card">
        <h1>Progress Studi</h1>
        <p class="muted">Halaman ini digunakan untuk mengupload KHS/progress akademik dan melihat catatan dari dosen wali.</p>

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

    @if($user->role === 'mahasiswa')
        <div class="card">
            <h2>Upload Progress Studi</h2>

            <form action="{{ route('progress-studi.store') }}" method="POST" enctype="multipart/form-data" class="grid">
                @csrf

                <div class="form-group">
                    <label>Semester</label>
                    <input type="number" name="semester" min="1" placeholder="Contoh: 4" required>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>IP Semester</label>
                        <input type="number" step="0.01" name="ip" min="0" max="4" placeholder="Contoh: 3.50">
                    </div>

                    <div class="form-group">
                        <label>IPK</label>
                        <input type="number" step="0.01" name="ipk" min="0" max="4" placeholder="Contoh: 3.60">
                    </div>
                </div>

                <div class="form-group">
                    <label>SKS Lulus</label>
                    <input type="number" name="sks_lulus" min="0" placeholder="Contoh: 80">
                </div>

                <div class="form-group">
                    <label>Upload File KHS</label>
                    <input type="file" name="file_khs" accept=".pdf,.jpg,.jpeg,.png">
                    <small class="muted">Format: PDF, JPG, JPEG, PNG. Maksimal 5 MB.</small>
                </div>

                <div class="form-group">
                    <label>Catatan Mahasiswa</label>
                    <textarea name="catatan_mahasiswa" placeholder="Contoh: Mohon arahan untuk pengambilan mata kuliah semester depan."></textarea>
                </div>

                <button type="submit">Upload Progress</button>
            </form>
        </div>
    @endif

    <div class="card">
        <h2>Daftar Progress Studi</h2>

        @forelse($progressStudis as $progress)
            <div class="info-card" style="margin-bottom: 16px;">
                <h3>{{ $progress->mahasiswa->name ?? '-' }}</h3>

                <p>
                    <b>Semester:</b> {{ $progress->semester ?? '-' }} <br>
                    <b>IP:</b> {{ $progress->ip ?? '-' }} <br>
                    <b>IPK:</b> {{ $progress->ipk ?? '-' }} <br>
                    <b>SKS Lulus:</b> {{ $progress->sks_lulus ?? '-' }} <br>
                    <b>Status:</b> {{ strtoupper(str_replace('_', ' ', $progress->status)) }}
                </p>

                @if($progress->file_khs)
                    <p>
                        <b>File KHS:</b>
                        <a href="{{ asset('storage/' . $progress->file_khs) }}" target="_blank">Lihat File</a>
                    </p>
                @endif

                @if($progress->catatan_mahasiswa)
                    <p>
                        <b>Catatan Mahasiswa:</b><br>
                        {{ $progress->catatan_mahasiswa }}
                    </p>
                @endif

                @if($progress->catatan_dosen)
                    <p>
                        <b>Catatan Dosen:</b><br>
                        {{ $progress->catatan_dosen }}
                    </p>
                @endif

                @if(in_array($user->role, ['dosen', 'admin']))
                    <form action="{{ route('progress-studi.review', $progress) }}" method="POST" style="margin-top: 12px;">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label>Status Review</label>
                            <select name="status" required>
                                <option value="menunggu_review" {{ $progress->status === 'menunggu_review' ? 'selected' : '' }}>Menunggu Review</option>
                                <option value="sudah_direview" {{ $progress->status === 'sudah_direview' ? 'selected' : '' }}>Sudah Direview</option>
                                <option value="perlu_perbaikan" {{ $progress->status === 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Catatan Dosen</label>
                            <textarea name="catatan_dosen" placeholder="Tulis catatan atau arahan untuk mahasiswa...">{{ $progress->catatan_dosen }}</textarea>
                        </div>

                        <button type="submit">Simpan Review</button>
                    </form>
                @endif

                @if($user->role === 'admin' || $progress->mahasiswa_id === $user->id)
                    <form action="{{ route('progress-studi.destroy', $progress) }}" method="POST" style="margin-top: 12px;" onsubmit="return confirm('Yakin ingin menghapus progress ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn-danger" type="submit">Hapus Progress</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="muted">Belum ada progress studi.</p>
        @endforelse
    </div>
</div>
@endsection