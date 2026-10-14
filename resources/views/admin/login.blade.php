<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk · Panel Portofolio</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:opsz,wght,SOFT@9..144,500..700,100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="login">
    <div class="login-card">
        <h1>Masuk ke panel</h1>
        <p class="meta">Kelola profil, proyek, dan pesan portofolio Anda.</p>

        @if($errors->any())<div class="alert alert-err" role="alert">{{ $errors->first() }}</div>@endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <label class="field">Email
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </label>
            <label class="field">Kata sandi
                <input type="password" name="password" required autocomplete="current-password">
            </label>
            <label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya di perangkat ini</label>
            <button class="btn btn-primary" type="submit">Masuk</button>
        </form>
        <p class="meta" style="margin:18px 0 0"><a href="{{ route('home') }}">Kembali ke situs</a></p>
    </div>
</div>
</body>
</html>
