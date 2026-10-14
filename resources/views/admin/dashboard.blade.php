@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="head"><h1>Dashboard</h1><a class="btn btn-soft" href="{{ route('home') }}" target="_blank" rel="noopener">Lihat situs</a></div>

<div class="stat-grid">
    @foreach($stats as $s)
        <a class="stat" href="{{ route($s['route']) }}"><strong>{{ $s['value'] }}</strong><span>{{ $s['label'] }}</span></a>
    @endforeach
</div>

<div class="panel">
    <h2>Pesan terbaru</h2>
    @forelse($latestMessages as $m)
        <p style="margin:0 0 10px">
            <a href="{{ route('admin.messages.show', $m) }}" @if(! $m->is_read) style="font-weight:700" @endif>{{ $m->subject ?: 'Tanpa subjek' }}</a>
            <span class="meta"> · {{ $m->name }} · {{ $m->created_at->diffForHumans() }}</span>
        </p>
    @empty
        <p class="empty">Belum ada pesan masuk. Pesan dari formulir kontak akan muncul di sini.</p>
    @endforelse
</div>

<div class="panel">
    <h2>Mulai dari mana?</h2>
    <p class="meta" style="margin:0">Lengkapi <a href="{{ route('admin.profile.edit') }}">profil</a>, lalu tambahkan <a href="{{ route('admin.projects.create') }}">proyek pertama</a>. Semua perubahan langsung tampil di situs.</p>
</div>
@endsection
