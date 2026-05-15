@extends('layouts.app', ['title' => 'Dashboard SIMBIM'])

@section('content')
<div class="container grid">
    <section class="card hero-card">
        <div class="hero-content page-header">
            <div>
                <div class="page-kicker">{{ strtoupper($user->role) }}</div>
                <h1>Halo, {{ $user->name }}</h1>
                @if($user->role === 'admin')
                    <p class="muted">Pusat kendali SIMBIM untuk mengatur data pengguna, identitas registrasi, jadwal, progress studi, dan monitoring akademik.</p>
                @elseif($user->role === 'dosen')
                    <p class="muted">Pantau mahasiswa bimbingan, balas obrolan, proses jadwal, review progress, dan beri rekomendasi mata kuliah.</p>
                @else
                    <p class="muted">Ajukan jadwal, pantau progress studi, baca rekomendasi mata kuliah, dan lanjutkan obrolan bersama dosen wali.</p>
                @endif
            </div>
            <div class="page-actions">
                @if($user->role === 'admin')
                    <a class="btn" href="{{ route('identitas-registrasi.index') }}">Tambah Identitas</a>
                    <a class="btn btn-secondary" href="{{ route('data-mahasiswa.index') }}">Kelola Mahasiswa</a>
                @elseif($user->role === 'dosen')
                    <a class="btn" href="{{ route('chat.index') }}">Buka Obrolan</a>
                    <a class="btn btn-secondary" href="{{ route('data-mahasiswa.index') }}">Mahasiswa Bimbingan</a>
                @else
                    <a class="btn" href="{{ route('jadwal.index') }}">Ajukan Jadwal</a>
                    <a class="btn btn-secondary" href="{{ route('chat.index') }}">Buka Obrolan</a>
                @endif
            </div>
        </div>
    </section>

    @if($user->role === 'admin')
        <section class="grid grid-4">
            <div class="card stat-card"><div class="stat-number">{{ $totalUsers }}</div><div class="stat-label">Total User</div></div>
            <div class="card stat-card"><div class="stat-number">{{ $totalIdentitas }}</div><div class="stat-label">Identitas Registrasi</div></div>
            <div class="card stat-card"><div class="stat-number">{{ $jadwalMenunggu }}</div><div class="stat-label">Jadwal Menunggu</div></div>
            <div class="card stat-card"><div class="stat-number">{{ $progressPending }}</div><div class="stat-label">Progress Perlu Review</div></div>
        </section>

        <section class="grid grid-3">
            <div class="card">
                <h2>Admin Center</h2>
                <p class="muted">Aksi cepat yang paling sering dipakai admin.</p>
                <div class="feature-grid">
                    <a class="feature-card" href="{{ route('identitas-registrasi.index') }}"><span class="feature-icon">✦</span><span><b>Identitas</b><br><small class="muted">Tambah NIM/NIDN</small></span></a>
                    <a class="feature-card" href="{{ route('data-dosen.index') }}"><span class="feature-icon">◇</span><span><b>Data Dosen</b><br><small class="muted">Kelola dosen wali</small></span></a>
                    <a class="feature-card" href="{{ route('data-mahasiswa.index') }}"><span class="feature-icon">◉</span><span><b>Data Mahasiswa</b><br><small class="muted">Pasangkan dosen wali</small></span></a>
                </div>
            </div>

            <div class="card">
                <h2>Kondisi Sistem</h2>
                <div class="table-like">
                    <div class="list-row"><span>Identitas belum dipakai</span><b>{{ $identitasBelumDipakai }}</b></div>
                    <div class="list-row"><span>Ruang bimbingan aktif</span><b>{{ $totalConversations }}</b></div>
                    <div class="list-row"><span>Total rekomendasi</span><b>{{ $totalRekomendasi }}</b></div>
                    <div class="list-row"><span>Jadwal selesai</span><b>{{ $jadwalSelesai }}</b></div>
                </div>
            </div>

            <div class="card">
                <h2>Monitoring Jadwal</h2>
                @forelse($recentJadwals as $jadwal)
                    <div class="info-card">
                        <b>{{ $jadwal->topik }}</b>
                        <div class="muted">{{ $jadwal->mahasiswa->name ?? '-' }} • {{ strtoupper($jadwal->status) }}</div>
                    </div>
                @empty
                    <div class="empty">Belum ada jadwal.</div>
                @endforelse
            </div>
        </section>
    @else
        <section class="grid grid-4">
            <div class="card stat-card"><div class="stat-number">{{ $totalConversations }}</div><div class="stat-label">Ruang Bimbingan</div></div>
            <div class="card stat-card"><div class="stat-number">{{ $jadwalMenunggu }}</div><div class="stat-label">Jadwal Menunggu</div></div>
            <div class="card stat-card"><div class="stat-number">{{ $progressPending }}</div><div class="stat-label">Progress Pending</div></div>
            <div class="card stat-card"><div class="stat-number">{{ $totalRekomendasi }}</div><div class="stat-label">Rekomendasi</div></div>
        </section>

        <section class="grid grid-2">
            <div class="card">
                <div class="page-header">
                    <div><h2>Jadwal Terbaru</h2><p class="muted">Pantau agenda bimbingan akademik.</p></div>
                    <a class="btn btn-secondary" href="{{ route('jadwal.index') }}">Lihat Jadwal</a>
                </div>
                @forelse($recentJadwals as $jadwal)
                    <div class="info-card">
                        <span class="badge {{ $jadwal->status === 'selesai' ? 'green' : ($jadwal->status === 'menunggu' ? 'warning' : '') }}">{{ strtoupper($jadwal->status) }}</span>
                        <h3 style="margin-top:10px;">{{ $jadwal->topik }}</h3>
                        <p class="muted" style="margin-bottom:0;">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }} • {{ substr($jadwal->jam, 0, 5) }}</p>
                    </div>
                @empty
                    <div class="empty">Belum ada jadwal bimbingan.</div>
                @endforelse
            </div>

            <div class="card">
                <div class="page-header">
                    <div><h2>Pesan Terbaru</h2><p class="muted">Obrolan terakhir dari ruang bimbingan.</p></div>
                    <a class="btn btn-secondary" href="{{ route('chat.index') }}">Obrolan</a>
                </div>
                @forelse($latestMessages as $message)
                    <div class="row-link" style="margin-bottom:10px">
                        <div class="row-left">
                            <div class="avatar">{{ strtoupper(substr($message->sender->name, 0, 1)) }}</div>
                            <div>
                                <b>{{ $message->sender->name }}</b>
                                <div class="muted truncate">{{ $message->message }}</div>
                            </div>
                        </div>
                        <small class="muted">{{ $message->created_at->format('H:i') }}</small>
                    </div>
                @empty
                    <div class="empty">Belum ada pesan.</div>
                @endforelse
            </div>
        </section>
    @endif
</div>
@endsection
