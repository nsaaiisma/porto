@extends('layouts.admin')
@section('title', 'Pengalaman')

@section('content')
<div class="head"><h1>Pengalaman</h1></div>

<form class="panel" method="POST" action="{{ route('admin.experiences.store') }}">
    @csrf
    <h2>Tambah pengalaman</h2>
    @include('admin.experiences._form')
</form>

<div class="panel table-wrap">
    <table>
        <thead><tr><th>Posisi</th><th>Jenis</th><th>Periode</th><th>Urutan</th><th></th></tr></thead>
        <tbody>
        @forelse($experiences as $e)
            <tr>
                <td><strong>{{ $e->role }}</strong><br><span class="meta">{{ $e->organization }}</span></td>
                <td><span class="tag">{{ $e->type === 'work' ? 'Pekerjaan' : 'Pendidikan' }}</span></td>
                <td>{{ $e->period }}</td>
                <td>{{ $e->sort_order }}</td>
                <td class="actions">
                    <a class="btn btn-soft btn-sm" href="{{ route('admin.experiences.edit', $e) }}">Ubah</a>
                    <form method="POST" action="{{ route('admin.experiences.destroy', $e) }}" onsubmit="return confirm('Hapus pengalaman ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="empty">Belum ada pengalaman. Tambahkan yang pertama lewat formulir di atas.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
