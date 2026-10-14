@extends('layouts.admin')
@section('title', 'Pesan')

@section('content')
<div class="head"><h1>Pesan masuk</h1></div>

<div class="panel table-wrap">
    <table>
        <thead><tr><th>Pengirim</th><th>Subjek</th><th>Diterima</th><th></th></tr></thead>
        <tbody>
        @forelse($messages as $m)
            <tr class="{{ $m->is_read ? '' : 'unread' }}">
                <td>{{ $m->name }}<br><span class="meta">{{ $m->email }}</span></td>
                <td><a href="{{ route('admin.messages.show', $m) }}">{{ $m->subject ?: 'Tanpa subjek' }}</a></td>
                <td>{{ $m->created_at->diffForHumans() }}</td>
                <td class="actions">
                    <form method="POST" action="{{ route('admin.messages.destroy', $m) }}" onsubmit="return confirm('Hapus pesan ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="empty">Belum ada pesan. Pesan dari formulir kontak di situs akan muncul di sini.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
