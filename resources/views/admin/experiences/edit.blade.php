@extends('layouts.admin')
@section('title', 'Ubah pengalaman')

@section('content')
<div class="head"><h1>Ubah pengalaman</h1></div>
<form class="panel" method="POST" action="{{ route('admin.experiences.update', $experience) }}">
    @csrf @method('PUT')
    @include('admin.experiences._form')
</form>
@endsection
