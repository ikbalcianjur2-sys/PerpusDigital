
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital - Jelajahi Dunia Pengetahuan</title>
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6;
            --secondary: #1e40af;
            --accent: #f59e0b;
            --bg-light: #f8fafc;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* HEADER & NAVIGATION */
        .site-header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 1.5rem 2rem;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: transparent;
            transition: background 0.3s ease;
        }

        .site-header .logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--white);
            text-decoration: none;
        }

        .main-nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        .main-nav a {
            text-decoration: none;
            color: var(--white);
            font-weight: 500;
            padding: 0.5rem 0;
            position: relative;
        }
        
        .main-nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.3s ease;
        }

        .main-nav a:hover::after {
            width: 100%;
        }

        .main-nav a.btn-login {
            background: var(--accent);
            color: var(--white);
            padding: 0.6rem 1.5rem;
            border-radius: 9999px;
            transition: background 0.3s ease;
        }

        .main-nav a.btn-login:hover {
            background: #fca5a5;
        }
        
        .main-nav a.btn-login::after {
            display: none;
        }


        /* HERO SECTION */
        .hero {
            /* Ganti URL di bawah dengan path gambar Anda */
            background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            color: var(--white);
            padding: 12rem 2rem 6rem;
            text-align: center;
            position: relative;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }

        .hero p {
            font-size: clamp(1.1rem, 2vw, 1.3rem);
            opacity: 0.9;
            margin-bottom: 2.5rem;
        }

        /* CONTAINER */
        .container {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 2rem;
            position: relative;
            z-index: 10;
        }

        /* QUOTE SECTION */
        .quote-section {
            margin-bottom: 4rem;
            margin-top: -8rem;
        }

        .quote-card {
            background: var(--white);
            border-left: 5px solid var(--accent);
            padding: 2.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .quote-card i {
            color: var(--accent);
            font-size: 1.5rem;
            margin-bottom: 1rem;
            display: block;
        }

        .quote-card p {
            font-size: 1.3rem;
            font-style: italic;
            color: var(--text-primary);
        }

        /* BOOKS SECTION */
        .books-section {
            margin-top: 3rem;
        }

        .section-title {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            color: var(--text-primary);
        }

        .section-title span {
            color: var(--primary);
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 2rem;
        }

        .book-card {
            background: var(--white);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .book-cover-wrapper {
            height: 300px;
            overflow: hidden;
        }

        .book-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .book-card:hover .book-cover {
            transform: scale(1.05);
        }

        .book-cover-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: var(--text-secondary);
        }

        .book-info {
            padding: 1.25rem;
        }

        .book-title {
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-primary);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 48px;
        }

        .book-author {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <a href="/" class="logo">PerpusDigital</a>
        <nav class="main-nav">
            <ul>
                <li><a href="/">Beranda</a></li>
                <li><a href="#">Informasi</a></li>
                <li><a href="{{ route('login') }}" class="btn-login">Log In</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Perpustakaan Digital</h1>
            <p>Membuka Pintu Menuju Pengetahuan Tanpa Batas. Jelajahi ribuan koleksi buku, baca, dan temukan wawasan baru setiap hari.</p>
        </div>
    </section>

    <div class="container">
        <div class="quote-section">
            <div class="quote-card">
                <i class="fa fa-quote-left"></i>
                <p>"{{ $quoteOfTheDay }}"</p>
            </div>
        </div>

        <div class="books-section">
            <h2 class="section-title">Buku <span>Terpopuler</span> Saat Ini</h2>
            <div class="books-grid">
                @forelse ($bukuPopuler as $buku)
                    <div class="book-card">
                        <div class="book-cover-wrapper">
                            @if ($buku->cover)
                                <img src="{{ asset('storage/' . $buku->cover) }}" alt="{{ $buku->judul }}" class="book-cover">
                            @else
                                <div class="book-cover-placeholder">
                                    <i class="fa fa-book"></i>
                                </div>
                            @endif
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">{{ $buku->judul }}</h3>
                            <p class="book-author">{{ $buku->penulis }}</p>
                        </div>
                    </div>
                @empty
                    <p style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-secondary);">Belum ada data peminjaman. Jadilah yang pertama meminjam buku!</p>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>