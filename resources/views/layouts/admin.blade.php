<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · Panel Portofolio</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:opsz,wght,SOFT@9..144,500..700,100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
@php $unread = \App\Models\Message::where('is_read', false)->count(); @endphp
<div class="shell">
    <aside class="side">
        <a class="logo" href="{{ route('admin.dashboard') }}">🌸 Panel Portofolio</a>
        <a class="item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a class="item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}">Profil</a>
        <a class="item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">Proyek</a>
        <a class="item {{ request()->routeIs('admin.skills.*') ? 'active' : '' }}" href="{{ route('admin.skills.index') }}">Keahlian</a>
        <a class="item {{ request()->routeIs('admin.experiences.*') ? 'active' : '' }}" href="{{ route('admin.experiences.index') }}">Pengalaman</a>
        <a class="item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">
            Pesan @if($unread)<span class="pill">{{ $unread }}</span>@endif
        </a>
        <div class="spacer"></div>
        <a class="item" href="{{ route('home') }}" target="_blank" rel="noopener">Lihat situs</a>
        <form method="POST" action="{{ route('admin.logout') }}">@csrf
            <button class="btn btn-soft" type="submit">Keluar</button>
        </form>
    </aside>

    <main class="main">
        @if(session('success'))<div class="alert alert-ok" role="status">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-err" role="alert">
                Ada isian yang perlu diperbaiki:
                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>
