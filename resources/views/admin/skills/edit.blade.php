@extends('layouts.admin')
@section('title', 'Ubah keahlian')

@section('content')
<div class="head"><h1>Ubah keahlian</h1></div>
<form class="panel" method="POST" action="{{ route('admin.skills.update', $skill) }}">
    @csrf @method('PUT')
    @include('admin.skills._form')
</form>
@endsection
