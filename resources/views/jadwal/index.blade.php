@extends('layouts.app', ['title' => 'Jadwal Bimbingan'])

@section('content')
<div class="container grid">
    <div class="card">
        <h1>Jadwal Bimbingan</h1>
        <p class="muted">Halaman ini digunakan untuk mengajukan, melihat, dan mengelola jadwal bimbingan akademik.</p>

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
            <h2>Ajukan Jadwal Bimbingan</h2>

            <form action="{{ route('jadwal.store') }}" method="POST" class="grid">
                @csrf

                <div class="form-group">
                    <label>Dosen Wali</label>
                    <select name="dosen_id" required>
                        <option value="">Pilih dosen wali</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" required>
                    </div>

                    <div class="form-group">
                        <label>Jam</label>
                        <input type="time" name="jam" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Topik Bimbingan</label>
                    <input type="text" name="topik" placeholder="Contoh: Konsultasi KRS semester depan" required>
                </div>

                <div class="form-group">
                    <label>Keterangan Tambahan</label>
                    <textarea name="keterangan" placeholder="Tulis keterangan jika ada..."></textarea>
                </div>

                <button type="submit">Ajukan Jadwal</button>
            </form>
        </div>
    @endif

    <div class="card">
        <h2>Daftar Jadwal Bimbingan</h2>

        @forelse($jadwals as $jadwal)
            <div class="info-card" style="margin-bottom: 16px;">
                <h3>{{ $jadwal->topik }}</h3>

                <p>
                    <b>Mahasiswa:</b> {{ $jadwal->mahasiswa->name ?? '-' }} <br>
                    <b>Dosen:</b> {{ $jadwal->dosen->name ?? '-' }} <br>
                    <b>Tanggal:</b> {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }} <br>
                    <b>Jam:</b> {{ substr($jadwal->jam, 0, 5) }} <br>
                    <b>Status:</b> {{ strtoupper($jadwal->status) }}
                </p>

                @if($jadwal->keterangan)
                    <p>
                        <b>Keterangan Mahasiswa:</b><br>
                        {{ $jadwal->keterangan }}
                    </p>
                @endif

                @if($jadwal->catatan_dosen)
                    <p>
                        <b>Catatan Dosen:</b><br>
                        {{ $jadwal->catatan_dosen }}
                    </p>
                @endif

                @if($user->role === 'dosen' && $jadwal->status === 'menunggu')
                    <form action="{{ route('jadwal.approve', $jadwal) }}" method="POST" style="margin-top: 12px;">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label>Catatan Persetujuan</label>
                            <textarea name="catatan_dosen" placeholder="Contoh: Jadwal disetujui, silakan hadir tepat waktu."></textarea>
                        </div>

                        <button type="submit">Setujui Jadwal</button>
                    </form>

                    <form action="{{ route('jadwal.reject', $jadwal) }}" method="POST" style="margin-top: 12px;">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label>Alasan Penolakan</label>
                            <textarea name="catatan_dosen" placeholder="Contoh: Mohon pilih jadwal lain."></textarea>
                        </div>

                        <button type="submit">Tolak Jadwal</button>
                    </form>
                @endif

                @if($user->role === 'dosen' && $jadwal->status === 'disetujui')
                    <form action="{{ route('jadwal.finish', $jadwal) }}" method="POST" style="margin-top: 12px;">
                        @csrf
                        @method('PATCH')
                        <button type="submit">Tandai Selesai</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="muted">Belum ada jadwal bimbingan.</p>
        @endforelse
    </div>
</div>
@endsection