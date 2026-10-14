@extends('layouts.admin')
@section('title', 'Tambah proyek')

@section('content')
<div class="head"><h1>Tambah proyek</h1></div>
<form class="panel" method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.projects._form')
</form>
@endsection
