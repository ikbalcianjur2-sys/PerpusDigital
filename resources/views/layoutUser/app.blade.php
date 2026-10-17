<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PerpusDigital</title>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
</head>
<body>
    <aside>
        <div class="logo">
            <h1>PerpusDigital</h1>
        </div>
        <nav class="sidebar">
            <ul>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('books.index') }}">Buku</a></li>
                <li><a href="{{ route('authors.index') }}">Penerbit</a></li>
                <li><a href="{{ route('categories.index') }}">Kategori</a></li>
                <li><a href="{{ route('users.index') }}">Riwayat Peminjaman</a></li>
            </ul>
        </nav>
</body>
</html>