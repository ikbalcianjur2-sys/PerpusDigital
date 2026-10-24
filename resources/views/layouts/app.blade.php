<!-- Ganti isi resources/views/layouts/app.blade.php dengan ini -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ \App\Models\Setting::current()->nama_perpustakaan ?? 'Perpustakaan' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <div class="app-wrapper">
        {{-- SIDEBAR --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
    <span class="brand-icon"></span>
    <span class="brand-text">{{ \App\Models\Setting::current()->nama_perpustakaan ?? 'Perpustakaan Digital' }}</span>
</div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"></span> Dashboard
                </a>
                <a href="{{ route('buku.index') }}" class="nav-item {{ request()->routeIs('buku.*') ? 'active' : '' }}">
                    <span class="nav-icon"></span> Data Buku
                </a>
                <a href="{{ route('kategori.index') }}" class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                    <span class="nav-icon"></span> Kategori
                </a>
                <a href="{{ route('peminjaman.index') }}" class="nav-item {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}" style="position:relative;">
                    <span class="nav-icon"></span> Peminjaman
                    @if (($jumlahTerlambatGlobal ?? 0) > 0)
                        <span class="nav-badge">{{ $jumlahTerlambatGlobal }}</span>
                    @endif
                </a>
                <a href="{{ route('anggota.index') }}" class="nav-item {{ request()->routeIs('anggota.*') ? 'active' : '' }}">
                    <span class="nav-icon"></span> Anggota
                </a>
                <a href="{{ route('ranking.index') }}" class="nav-item {{ request()->routeIs('ranking.*') ? 'active' : '' }}">
                    <span class="nav-icon"></span> Ranking Buku
                </a>
                <a href="{{ route('laporan.index') }}" class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <span class="nav-icon"></span> Laporan
                </a>
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('setting.edit') }}" class="nav-item {{ request()->routeIs('setting.*') ? 'active' : '' }}">
                        <span class="nav-icon"></span> Pengaturan
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <p style="margin:0 0 8px 0;">&copy; {{ date('Y') }} {{ \App\Models\Setting::current()->nama_perpustakaan ?? 'PerpusKu' }}</p>
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm btn-block" style="color:#fff; border-color:rgba(255,255,255,0.4);">
                             Keluar
                        </button>
                    </form>
                @endauth
            </div>
        </aside>

        <div class="overlay" id="overlay"></div>

        {{-- MAIN --}}
        <div class="main-content">
            <header class="topbar">
                <button class="btn-hamburger" id="btnHamburger" aria-label="Buka menu">☰</button>
                <h1 class="page-title">@yield('title', 'Dashboard')</h1>
                <div class="topbar-right">
                    @auth
                        <span style="margin-right:10px;">👤 {{ auth()->user()->name }}</span>
                    @endauth
                    <span class="topbar-date" id="topbarDate"></span>
                </div>
            </header>

            <main class="content-area">
                @if (session('success'))
                    <div class="alert alert-success" data-autohide>
                        <span>✅ {{ session('success') }}</span>
                        <button class="alert-close" type="button">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error" data-autohide>
                        <span>⚠️ {{ session('error') }}</span>
                        <button class="alert-close" type="button">&times;</button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error" data-autohide>
                        <span>⚠️ Periksa kembali isian formulir Anda:
                            <ul style="margin:6px 0 0 18px;">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </span>
                        <button class="alert-close" type="button">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>