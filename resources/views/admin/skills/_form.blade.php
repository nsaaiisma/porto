<div class="form-grid">
    <label class="field">Nama keahlian
        <input name="name" value="{{ old('name', $skill->name) }}" required maxlength="60" placeholder="Laravel">
    </label>
    <label class="field">Kelompok
        <input name="category" list="skill-cats" value="{{ old('category', $skill->category ?: 'Backend') }}" required maxlength="50">
        <datalist id="skill-cats">
            @foreach($categories as $c)<option value="{{ $c }}">@endforeach
            <option value="Backend"><option value="Frontend"><option value="Tools">
        </datalist>
    </label>
    <label class="field">Tingkat penguasaan (0–100)
        <input type="number" name="level" min="0" max="100" value="{{ old('level', $skill->level ?? 80) }}" required>
    </label>
    <label class="field">Urutan tampil
        <input type="number" name="sort_order" value="{{ old('sort_order', $skill->sort_order ?? 0) }}">
    </label>
</div>
<div class="form-actions">
    <button class="btn btn-primary" type="submit">Simpan keahlian</button>
    @if($skill->exists)<a class="btn btn-soft" href="{{ route('admin.skills.index') }}">Batal</a>@endif
</div>
