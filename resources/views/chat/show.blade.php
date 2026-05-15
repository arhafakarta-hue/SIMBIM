@extends('layouts.app', ['title' => 'Chat dengan '.$otherUser->name])

@section('content')
<div class="container chat-page">
    <aside class="chat-sidebar">
        <div class="chat-sidebar-header">
            <h3 style="margin-bottom:4px">Info Bimbingan</h3>
            <p class="muted" style="margin-bottom:0">{{ $conversation->judul }}</p>
        </div>
        <div class="chat-list">
            <div class="info-card">
                <b>Dosen Wali</b>
                <div class="muted">{{ $conversation->dosen->name }}</div>
                <small class="muted">{{ $conversation->dosen->email }}</small>
            </div>
            <div class="info-card">
                <b>Mahasiswa</b>
                <div class="muted">{{ $conversation->mahasiswa->name }}</div>
                <small class="muted">{{ $conversation->mahasiswa->nim ?? '-' }} • Semester {{ $conversation->mahasiswa->semester ?? '-' }}</small>
            </div>
            <div class="info-card">
                <b>Rekomendasi Mata Kuliah</b>
                <div class="muted">{{ $conversation->rekomendasi_mata_kuliah ?? 'Belum ada rekomendasi.' }}</div>
            </div>
            <div class="info-card">
                <b>Catatan Dosen</b>
                <div class="muted">{{ $conversation->catatan_dosen ?? 'Belum ada catatan.' }}</div>
            </div>
            <a class="btn btn-secondary" href="{{ route('chat.index') }}">Kembali</a>
        </div>
    </aside>

    <section class="chat-panel">
        <header class="chat-header">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px">
                <div class="row-left">
                    <div class="avatar">{{ strtoupper(substr($otherUser->name, 0, 1)) }}</div>
                    <div>
                        <h2 style="margin-bottom:2px">{{ $otherUser->name }}</h2>
                        <div class="muted">{{ ucfirst($otherUser->role) }} • <span id="connection-dot" class="status-dot"></span> <span id="connection-status">Menghubungkan real-time...</span></div>
                    </div>
                </div>
                <a class="btn btn-secondary" href="{{ route('chat.index') }}">Daftar Chat</a>
            </div>
        </header>

        <div id="messages" class="messages">
            @foreach($messages as $message)
                <div class="message {{ $message->sender_id === auth()->id() ? 'mine' : '' }}" data-message-id="{{ $message->id }}">
                    <div class="bubble">
                        <div class="bubble-head">
                            <span>{{ $message->sender->name }}</span>
                            <span>{{ $message->created_at->format('H:i') }}</span>
                        </div>
                        <div class="bubble-text">{{ $message->message }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <form id="chat-form" class="chat-form" method="POST" action="{{ route('chat.store', $conversation) }}">
            @csrf
            <textarea id="message-input" name="message" placeholder="Tulis pesan bimbingan..." maxlength="1000" required></textarea>
            <button type="submit">Kirim</button>
        </form>
    </section>
</div>

<script>
    const CURRENT_USER_ID = @json(auth()->id());
    const CONVERSATION_ID = @json($conversation->id);
    const POST_URL = @json(route('chat.store', $conversation));
    const FETCH_URL = @json(route('chat.messages', $conversation));
    const REVERB = @json($reverb);
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

    const messagesEl = document.getElementById('messages');
    const formEl = document.getElementById('chat-form');
    const inputEl = document.getElementById('message-input');
    const statusEl = document.getElementById('connection-status');
    const dotEl = document.getElementById('connection-dot');

    let lastId = Number(@json($messages->max('id') ?? 0));
    let socketConnected = false;

    function scrollBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function appendMessage(data) {
        if (!data || !data.id) return;
        if (document.querySelector(`[data-message-id="${data.id}"]`)) return;

        lastId = Math.max(lastId, Number(data.id));
        const mine = Number(data.sender_id) === Number(CURRENT_USER_ID);
        const wrapper = document.createElement('div');
        wrapper.className = `message ${mine ? 'mine' : ''}`;
        wrapper.dataset.messageId = data.id;
        wrapper.innerHTML = `
            <div class="bubble">
                <div class="bubble-head">
                    <span>${escapeHtml(data.sender_name ?? 'User')}</span>
                    <span>${escapeHtml(data.time ?? '')}</span>
                </div>
                <div class="bubble-text">${escapeHtml(data.message ?? '')}</div>
            </div>
        `;
        messagesEl.appendChild(wrapper);
        scrollBottom();
    }

    function setConnectionStatus(connected, text) {
        socketConnected = connected;
        statusEl.textContent = text;
        dotEl.classList.toggle('online', connected);
    }

    formEl.addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = inputEl.value.trim();
        if (!message) return;

        inputEl.value = '';
        inputEl.focus();

        try {
            const response = await fetch(POST_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                body: JSON.stringify({ message }),
            });

            if (!response.ok) throw new Error('Gagal mengirim pesan.');
            const data = await response.json();
            appendMessage(data);
        } catch (error) {
            alert(error.message || 'Gagal mengirim pesan.');
            inputEl.value = message;
        }
    });

    inputEl.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            formEl.requestSubmit();
        }
    });

    async function fetchNewMessages() {
        try {
            const response = await fetch(`${FETCH_URL}?after_id=${lastId}`, {
                headers: { 'Accept': 'application/json' },
            });
            if (!response.ok) return;
            const items = await response.json();
            items.forEach(appendMessage);
        } catch (error) {
            // Fallback polling sengaja dibuat diam agar tidak mengganggu user.
        }
    }

    function connectReverb() {
        if (!REVERB.key || !REVERB.host || !REVERB.port) {
            setConnectionStatus(false, 'Realtime belum dikonfigurasi, memakai polling.');
            return;
        }

        const wsScheme = REVERB.scheme === 'https' ? 'wss' : 'ws';
        const url = `${wsScheme}://${REVERB.host}:${REVERB.port}/app/${REVERB.key}?protocol=7&client=simbim&version=1.0&flash=false`;

        try {
            const ws = new WebSocket(url);

            ws.addEventListener('open', () => {
                ws.send(JSON.stringify({
                    event: 'pusher:subscribe',
                    data: { channel: `conversation.${CONVERSATION_ID}` },
                }));
                setConnectionStatus(true, 'Realtime aktif');
            });

            ws.addEventListener('message', (event) => {
                let payload;
                try { payload = JSON.parse(event.data); } catch { return; }

                if (payload.event === 'MessageSent') {
                    let data = payload.data;
                    if (typeof data === 'string') {
                        try { data = JSON.parse(data); } catch { return; }
                    }
                    appendMessage(data);
                }
            });

            ws.addEventListener('close', () => {
                setConnectionStatus(false, 'Realtime terputus, memakai polling.');
                setTimeout(connectReverb, 3000);
            });

            ws.addEventListener('error', () => {
                setConnectionStatus(false, 'Realtime error, memakai polling.');
            });
        } catch (error) {
            setConnectionStatus(false, 'Realtime belum aktif, memakai polling.');
        }
    }

    scrollBottom();
    connectReverb();
    setInterval(fetchNewMessages, 2500);
</script>
@endsection
