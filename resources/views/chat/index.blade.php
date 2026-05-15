@extends('layouts.app', ['title' => 'Obrolan Bimbingan'])

@section('content')
<div class="container grid">
    <div class="card">
        <h1>Obrolan Bimbingan</h1>
        <p class="muted">Pilih ruang bimbingan. Dosen dan mahasiswa bisa saling mengirim pesan dari perangkat berbeda.</p>
    </div>

    <div class="card">
        <div class="table-like">
            @forelse($conversations as $conversation)
                @php
                    $other = auth()->id() === $conversation->dosen_id ? $conversation->mahasiswa : $conversation->dosen;
                    $last = $conversation->messages->first();
                @endphp
                <a class="row-link" href="{{ route('chat.show', $conversation) }}">
                    <div class="row-left">
                        <div class="avatar">{{ strtoupper(substr($other->name, 0, 1)) }}</div>
                        <div style="min-width:0">
                            <b>{{ $conversation->judul }}</b>
                            <div class="muted">Dengan {{ $other->name }} • {{ ucfirst($other->role) }}</div>
                            <div class="muted truncate" style="margin-top:4px">{{ $last?->message ?? 'Belum ada pesan' }}</div>
                        </div>
                    </div>
                    <span class="badge green">{{ $conversation->status }}</span>
                </a>
            @empty
                <div class="empty">Belum ada ruang bimbingan.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
