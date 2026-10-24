<!-- Taruh file ini di: resources/views/auth/login.blade.php
     (buat folder "auth" di dalam resources/views/ kalau belum ada) -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ \App\Models\Setting::current()->nama_perpustakaan ?? 'Perpustakaan' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-brand">
                <span class="brand-icon"></span>
                <span>{{ \App\Models\Setting::current()->nama_perpustakaan ?? 'PerpusKu' }}</span>
            </div>
            <p class="auth-subtitle">Masuk untuk mengelola perpustakaan Anda</p>

            @if (session('success'))
                <div class="alert alert-success" data-autohide>
                    <span>✅ {{ session('success') }}</span>
                    <button class="alert-close" type="button">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <span>⚠️ {{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                </div>
<div class="form-group">
    <label>Password</label>

    <div style="position: relative;">
        <input
            type="password"
            name="password"
            id="password"
            required
            placeholder="••••••••"
            style="padding-right: 45px;"
        >

        <button
            type="button"
            id="togglePassword"
            style="
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                border: none;
                background: transparent;
                cursor: pointer;
                font-size: 18px;
            "
        >👁️</button>
    </div>
</div>
                <div class="form-group" style="display:flex; align-items:center; justify-content:space-between;">
                    <label style="display:flex; align-items:center; gap:6px; margin:0; font-weight:400; color:var(--gray-700); font-size:13px;">
                        <input type="checkbox" name="remember" style="width:auto;"> Ingat saya
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
<script>
const password = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');

togglePassword.addEventListener('click', function () {

    if (password.type === 'password') {
        password.type = 'text';
        togglePassword.textContent = '🙈';
    } else {
        password.type = 'password';
        togglePassword.textContent = '👁️';
    }

});
</script>
</body>
</html>