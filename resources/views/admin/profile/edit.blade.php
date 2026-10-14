@extends('layouts.admin')
@section('title', 'Profil')

@section('content')
<div class="head"><h1>Profil</h1></div>

<form class="panel" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="form-grid">
        <label class="field">Nama lengkap
            <input name="name" value="{{ old('name', $profile->name) }}" required>
        </label>
        <label class="field">Jabatan
            <input name="title" value="{{ old('title', $profile->title) }}" required>
        </label>
        <label class="field full">Judul besar di halaman depan
            <input name="tagline" value="{{ old('tagline', $profile->tagline) }}" required maxlength="160">
        </label>
        <label class="field full">Peran yang berganti-ganti (pisahkan dengan koma)
            <input name="typing_roles" value="{{ old('typing_roles', $profile->typing_roles) }}">
            <small>Contoh: Laravel Developer, Pecinta UI, Pemecah masalah backend</small>
        </label>
        <label class="field full">Tentang saya
            <textarea name="about" rows="7">{{ old('about', $profile->about) }}</textarea>
            <small>Kosongkan satu baris untuk membuat paragraf baru.</small>
        </label>
        <label class="field">Email
            <input type="email" name="email" value="{{ old('email', $profile->email) }}">
        </label>
        <label class="field">Telepon
            <input name="phone" value="{{ old('phone', $profile->phone) }}">
        </label>
        <label class="field">Lokasi
            <input name="location" value="{{ old('location', $profile->location) }}">
        </label>
        <label class="field">Tahun pengalaman
            <input type="number" min="0" max="60" name="years_experience" value="{{ old('years_experience', $profile->years_experience) }}" required>
        </label>
        <label class="field">Link CV (opsional)
            <input type="url" name="cv_url" value="{{ old('cv_url', $profile->cv_url) }}" placeholder="https://...">
        </label>
        <label class="field">GitHub
            <input type="url" name="github" value="{{ old('github', $profile->github) }}" placeholder="https://github.com/...">
        </label>
        <label class="field">LinkedIn
            <input type="url" name="linkedin" value="{{ old('linkedin', $profile->linkedin) }}" placeholder="https://linkedin.com/in/...">
        </label>
        <label class="field">Instagram
            <input type="url" name="instagram" value="{{ old('instagram', $profile->instagram) }}" placeholder="https://instagram.com/...">
        </label>

        <div class="field full">
            Foto profil
            @if($profile->photo_url)
                <img class="preview" src="{{ $profile->photo_url }}" alt="Foto profil saat ini">
                <label class="check"><input type="checkbox" name="remove_photo" value="1"> Hapus foto saat ini</label>
            @endif
            <input type="file" name="photo" accept="image/png,image/jpeg,image/webp">
            <small>JPG, PNG, atau WebP. Maksimal 2 MB. Rasio 4:5 paling pas.</small>
        </div>
    </div>
    <div class="form-actions"><button class="btn btn-primary" type="submit">Simpan profil</button></div>
</form>

<form class="panel" method="POST" action="{{ route('admin.profile.password') }}">
    @csrf @method('PUT')
    <h2>Ganti kata sandi</h2>
    <div class="form-grid">
        <label class="field full">Kata sandi saat ini
            <input type="password" name="current_password" required autocomplete="current-password">
        </label>
        <label class="field">Kata sandi baru
            <input type="password" name="password" required minlength="8" autocomplete="new-password">
        </label>
        <label class="field">Ulangi kata sandi baru
            <input type="password" name="password_confirmation" required autocomplete="new-password">
        </label>
    </div>
    <div class="form-actions"><button class="btn btn-primary" type="submit">Ganti kata sandi</button></div>
</form>
@endsection
