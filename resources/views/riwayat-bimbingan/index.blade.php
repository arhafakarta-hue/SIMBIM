@extends('layouts.app', ['title' => 'Riwayat Bimbingan'])

@section('content')
<div class="container grid">
    <div class="card">
        <h1>Riwayat Bimbingan</h1>
        <p class="muted">Halaman ini menampilkan riwayat bimbingan akademik yang sudah diproses oleh dosen wali.</p>
    </div>

    <div class="card">
        <h2>Daftar Riwayat Bimbingan</h2>

        @forelse($riwayats as $riwayat)
            <div class="info-card" style="margin-bottom: 16px;">
                <h3>{{ $riwayat->topik }}</h3>

                <p>
                    <b>Mahasiswa:</b> {{ $riwayat->mahasiswa->name ?? '-' }} <br>
                    <b>Dosen Wali:</b> {{ $riwayat->dosen->name ?? '-' }} <br>
                    <b>Tanggal:</b> {{ \Carbon\Carbon::parse($riwayat->tanggal)->format('d M Y') }} <br>
                    <b>Jam:</b> {{ substr($riwayat->jam, 0, 5) }} <br>
                    <b>Status:</b> {{ strtoupper($riwayat->status) }}
                </p>

                @if($riwayat->keterangan)
                    <p>
                        <b>Keterangan Mahasiswa:</b><br>
                        {{ $riwayat->keterangan }}
                    </p>
                @endif

                @if($riwayat->catatan_dosen)
                    <p>
                        <b>Catatan Dosen:</b><br>
                        {{ $riwayat->catatan_dosen }}
                    </p>
                @endif
            </div>
        @empty
            <p class="muted">Belum ada riwayat bimbingan.</p>
        @endforelse
    </div>
</div>
@endsection