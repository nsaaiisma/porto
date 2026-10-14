<div class="form-grid">
    <label class="field">Judul proyek
        <input name="title" value="{{ old('title', $project->title) }}" required maxlength="120">
    </label>
    <label class="field">Kategori
        <input name="category" list="cats" value="{{ old('category', $project->category ?: 'Web') }}" required maxlength="50">
        <datalist id="cats">
            <option>Web</option><option>API</option><option>Mobile</option><option>UI/UX</option><option>Desktop</option>
        </datalist>
        <small>Kategori dipakai sebagai tombol filter di situs.</small>
    </label>
    <label class="field full">Ringkasan singkat
        <input name="summary" value="{{ old('summary', $project->summary) }}" required maxlength="255">
    </label>
    <label class="field full">Cerita lengkap proyek
        <textarea name="description" rows="6">{{ old('description', $project->description) }}</textarea>
        <small>Tampil di jendela detail saat kartu diklik.</small>
    </label>
    <label class="field full">Teknologi (pisahkan dengan koma)
        <input name="tech_stack" value="{{ old('tech_stack', $project->tech_stack) }}" placeholder="Laravel, MySQL, Tailwind CSS">
    </label>
    <label class="field">Link demo
        <input type="url" name="demo_url" value="{{ old('demo_url', $project->demo_url) }}" placeholder="https://...">
    </label>
    <label class="field">Link repositori
        <input type="url" name="repo_url" value="{{ old('repo_url', $project->repo_url) }}" placeholder="https://github.com/...">
    </label>
    <label class="field">Urutan tampil
        <input type="number" name="sort_order" value="{{ old('sort_order', $project->sort_order ?? 0) }}">
        <small>Angka kecil tampil lebih dulu.</small>
    </label>
    <div class="field">
        Status
        <label class="check"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $project->is_published))> Tampilkan di situs</label>
        <label class="check"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured))> Tandai sebagai unggulan</label>
    </div>
    <div class="field full">
        Gambar sampul
        @if($project->image_url)
            <img class="preview" src="{{ $project->image_url }}" alt="Sampul saat ini">
            <label class="check"><input type="checkbox" name="remove_image" value="1"> Hapus gambar saat ini</label>
        @endif
        <input type="file" name="image" accept="image/png,image/jpeg,image/webp">
        <small>JPG, PNG, atau WebP. Maksimal 2 MB. Rasio 16:10 paling pas. Tanpa gambar, situs memakai inisial judul.</small>
    </div>
</div>
<div class="form-actions">
    <button class="btn btn-primary" type="submit">Simpan proyek</button>
    <a class="btn btn-soft" href="{{ route('admin.projects.index') }}">Batal</a>
</div>
