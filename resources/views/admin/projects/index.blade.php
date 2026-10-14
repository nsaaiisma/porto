@extends('layouts.admin')
@section('title', 'Proyek')

@section('content')
<div class="head"><h1>Proyek</h1><a class="btn btn-primary" href="{{ route('admin.projects.create') }}">Tambah proyek</a></div>

<div class="panel table-wrap">
    <table>
        <thead><tr><th></th><th>Judul</th><th>Kategori</th><th>Status</th><th>Urutan</th><th></th></tr></thead>
        <tbody>
        @forelse($projects as $p)
            <tr>
                <td>@if($p->image_url)<img class="thumb" src="{{ $p->image_url }}" alt="">@else<span class="thumb"></span>@endif</td>
                <td><strong>{{ $p->title }}</strong><br><span class="meta">{{ \Illuminate\Support\Str::limit($p->summary, 60) }}</span></td>
                <td>{{ $p->category }}</td>
                <td>
                    <span class="tag {{ $p->is_published ? 'on' : '' }}">{{ $p->is_published ? 'Tayang' : 'Draf' }}</span>
                    @if($p->is_featured)<span class="tag">Unggulan</span>@endif
                </td>
                <td>{{ $p->sort_order }}</td>
                <td class="actions">
                    <a class="btn btn-soft btn-sm" href="{{ route('admin.projects.edit', $p) }}">Ubah</a>
                    <form method="POST" action="{{ route('admin.projects.destroy', $p) }}" onsubmit="return confirm('Hapus proyek ini? Tindakan ini tidak bisa dibatalkan.')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="empty">Belum ada proyek. Klik “Tambah proyek” untuk memulai.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
