<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SIMBIM' }}</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <script>
        (function () {
            const savedTheme = localStorage.getItem('simbim-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body class="{{ auth()->check() ? 'is-app' : 'is-auth' }}">
    @auth
        @php
            $role = auth()->user()->role;
            $navItems = [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => '⌂', 'roles' => ['admin', 'dosen', 'mahasiswa']],
                ['label' => 'Obrolan', 'route' => 'chat.index', 'icon' => '✉', 'roles' => ['dosen', 'mahasiswa']],
                ['label' => $role === 'admin' ? 'Monitoring Jadwal' : 'Jadwal', 'route' => 'jadwal.index', 'icon' => '◷', 'roles' => ['admin', 'dosen', 'mahasiswa']],
                ['label' => 'Data Mahasiswa', 'route' => 'data-mahasiswa.index', 'icon' => '◉', 'roles' => ['admin', 'dosen']],
                ['label' => 'Data Dosen', 'route' => 'data-dosen.index', 'icon' => '◇', 'roles' => ['admin']],
                ['label' => 'Identitas', 'route' => 'identitas-registrasi.index', 'icon' => '✦', 'roles' => ['admin']],
                ['label' => 'Progress Studi', 'route' => 'progress-studi.index', 'icon' => '▣', 'roles' => ['admin', 'dosen', 'mahasiswa']],
                ['label' => 'Rekomendasi', 'route' => 'rekomendasi-matkul.index', 'icon' => '✧', 'roles' => ['admin', 'dosen', 'mahasiswa']],
                ['label' => 'Riwayat', 'route' => 'riwayat-bimbingan.index', 'icon' => '↺', 'roles' => ['admin', 'dosen', 'mahasiswa']],
            ];
        @endphp

        <div class="app-shell">
            <aside class="sidebar" aria-label="Navigasi utama">
                <a class="sidebar-brand" href="{{ route('dashboard') }}">
                    <span class="brand-emblem" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24 5L42 13.5L24 22L6 13.5L24 5Z" fill="currentColor" opacity="0.95"/>
                            <path d="M12 18V30.5C12 34.2 17.4 38 24 38C30.6 38 36 34.2 36 30.5V18" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                            <path d="M18 24.5C20.7 26.1 27.3 26.1 30 24.5" stroke="currentColor" stroke-width="3" stroke-linecap="round" opacity="0.78"/>
                            <path d="M39 16V26" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                            <circle cx="39" cy="30" r="2.8" fill="currentColor"/>
                        </svg>
                    </span>
                    <span>
                        <span class="sidebar-title">SIMBIM</span><br>
                        <span class="sidebar-caption">Bimbingan Akademik</span>
                    </span>
                </a>

                <div class="nav-section">
                    <div class="nav-label">Menu {{ ucfirst($role) }}</div>
                    <div class="nav-list">
                        @foreach($navItems as $item)
                            @if(in_array($role, $item['roles']))
                                <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                                    <span class="nav-icon">{{ $item['icon'] }}</span>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="sidebar-footer">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="logout-button btn-danger" type="submit">Keluar</button>
                    </form>
                </div>
            </aside>

            <div class="main-area">
                <header class="topbar">
                    <div>
                        <div class="topbar-title">{{ $title ?? 'SIMBIM' }}</div>
                        <div class="muted">Sistem Informasi Bimbingan Akademik</div>
                    </div>

                    <div class="topbar-actions">
                        <button class="appearance-switch" type="button" data-theme-toggle aria-label="Ganti mode tampilan">
                            <span class="switch-icon sun" aria-hidden="true">☀</span>
                            <span class="switch-track"><span class="switch-thumb"></span></span>
                            <span class="switch-icon moon" aria-hidden="true">☾</span>
                        </button>

                        <div class="user-pill">
                            <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            <span>
                                <b>{{ auth()->user()->name }}</b><br>
                                <small class="muted">{{ strtoupper($role) }}</small>
                            </span>
                        </div>
                    </div>
                </header>

                <main class="main">
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        @yield('content')
    @endauth

    <script>
        (function () {
            const toggle = document.querySelector('[data-theme-toggle]');
            const apply = () => {
                const theme = document.documentElement.getAttribute('data-theme') || 'light';
                if (toggle) toggle.setAttribute('data-active-theme', theme);
            };
            apply();
            if (toggle) {
                toggle.addEventListener('click', function () {
                    const current = document.documentElement.getAttribute('data-theme') || 'light';
                    const next = current === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', next);
                    localStorage.setItem('simbim-theme', next);
                    apply();
                });
            }
        })();
    </script>
</body>
</html>
