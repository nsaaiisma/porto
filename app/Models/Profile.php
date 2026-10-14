<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    protected $guarded = [];

    /** Profil hanya ada satu baris; dibuat otomatis jika belum ada. */
    public static function current(): self
    {
        return static::first() ?? static::create([
            'name' => 'Nama Anda',
            'title' => 'Full-stack Developer',
            'tagline' => 'Membangun aplikasi web yang rapi dan menyenangkan dipakai.',
        ]);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? Storage::url($this->photo) : null;
    }

    /** Daftar peran untuk efek mengetik di halaman depan. */
    public function getRolesAttribute(): array
    {
        $roles = array_values(array_filter(array_map('trim', explode(',', (string) $this->typing_roles))));

        return $roles ?: [$this->title];
    }
}
