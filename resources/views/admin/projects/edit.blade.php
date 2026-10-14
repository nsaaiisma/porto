@extends('layouts.admin')
@section('title', 'Ubah proyek')

@section('content')
<div class="head"><h1>Ubah proyek</h1></div>
<form class="panel" method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.projects._form')
</form>
@endsection
