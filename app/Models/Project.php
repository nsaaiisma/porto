<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'summary', 'description', 'tech_stack',
        'image', 'image_data', 'demo_url', 'repo_url', 'is_featured', 'is_published', 'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        // Blob data URI (disimpan di DB, permanen) diutamakan;
        // path file lama jadi fallback.
        return $this->image_data ?: ($this->image ? Storage::url($this->image) : null);
    }

    public function getTechListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->tech_stack))));
    }
}
