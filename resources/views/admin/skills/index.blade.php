@extends('layouts.admin')
@section('title', 'Keahlian')

@section('content')
<div class="head"><h1>Keahlian</h1></div>

<form class="panel" method="POST" action="{{ route('admin.skills.store') }}">
    @csrf
    <h2>Tambah keahlian</h2>
    @include('admin.skills._form')
</form>

<div class="panel table-wrap">
    <table>
        <thead><tr><th>Nama</th><th>Kelompok</th><th>Level</th><th>Urutan</th><th></th></tr></thead>
        <tbody>
        @forelse($skills as $s)
            <tr>
                <td><strong>{{ $s->name }}</strong></td>
                <td>{{ $s->category }}</td>
                <td>{{ $s->level }}%</td>
                <td>{{ $s->sort_order }}</td>
                <td class="actions">
                    <a class="btn btn-soft btn-sm" href="{{ route('admin.skills.edit', $s) }}">Ubah</a>
                    <form method="POST" action="{{ route('admin.skills.destroy', $s) }}" onsubmit="return confirm('Hapus keahlian ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="empty">Belum ada keahlian. Tambahkan yang pertama lewat formulir di atas.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
