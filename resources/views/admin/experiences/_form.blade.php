<div class="form-grid">
    <label class="field">Jenis
        <select name="type">
            <option value="work" @selected(old('type', $experience->type) === 'work')>Pekerjaan</option>
            <option value="education" @selected(old('type', $experience->type) === 'education')>Pendidikan</option>
        </select>
    </label>
    <label class="field">Periode
        <input name="period" value="{{ old('period', $experience->period) }}" required maxlength="60" placeholder="2022 – Sekarang">
    </label>
    <label class="field">Posisi / jurusan
        <input name="role" value="{{ old('role', $experience->role) }}" required maxlength="120">
    </label>
    <label class="field">Perusahaan / institusi
        <input name="organization" value="{{ old('organization', $experience->organization) }}" required maxlength="120">
    </label>
    <label class="field full">Deskripsi
        <textarea name="description" rows="4">{{ old('description', $experience->description) }}</textarea>
        <small>Tulis hasil nyata, misalnya “mempercepat halaman utama 40%”, bukan hanya daftar tugas.</small>
    </label>
    <label class="field">Urutan tampil
        <input type="number" name="sort_order" value="{{ old('sort_order', $experience->sort_order ?? 0) }}">
        <small>Angka kecil tampil lebih dulu (paling atas di linimasa).</small>
    </label>
</div>
<div class="form-actions">
    <button class="btn btn-primary" type="submit">Simpan pengalaman</button>
    @if($experience->exists)<a class="btn btn-soft" href="{{ route('admin.experiences.index') }}">Batal</a>@endif
</div>
