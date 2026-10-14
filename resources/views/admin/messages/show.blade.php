@extends('layouts.admin')
@section('title', 'Pesan')

@section('content')
<div class="head">
    <h1>{{ $message->subject ?: 'Tanpa subjek' }}</h1>
    <a class="btn btn-soft" href="{{ route('admin.messages.index') }}">Kembali</a>
</div>

<div class="panel">
    <p class="meta">
        Dari <strong>{{ $message->name }}</strong> (<a href="mailto:{{ $message->email }}">{{ $message->email }}</a>)
        · {{ $message->created_at->translatedFormat('d F Y, H:i') }}
    </p>
    <div class="msg-body">{{ $message->body }}</div>
    <div class="form-actions">
        <a class="btn btn-primary" href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.($message->subject ?: 'Pesan dari portofolio')) }}">Balas lewat email</a>
        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Hapus pesan ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger" type="submit">Hapus</button>
        </form>
    </div>
</div>
@endsection
