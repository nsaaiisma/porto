<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        return view('admin.projects.index', [
            'projects' => Project::orderBy('sort_order')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.projects.create', ['project' => new Project(['is_published' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);

        // Simpan gambar sebagai data URI di DB (permanen di D1),
        // bukan file di disk (ephemeral di Vercel).
        $data['image'] = null;
        $data['image_data'] = $request->hasFile('image')
            ? self::toDataUri($request->file('image'))
            : null;

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = null;
            $data['image_data'] = self::toDataUri($request->file('image'));
        } elseif ($request->boolean('remove_image')) {
            $data['image'] = null;
            $data['image_data'] = null;
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return back()->with('success', 'Proyek dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:50'],
            'summary' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'tech_stack' => ['nullable', 'string', 'max:255'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'repo_url' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        unset($data['image']);

        return $data;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'proyek';
        $slug = $base;
        $i = 2;
        while (Project::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    private static function toDataUri(UploadedFile $file): string
    {
        return 'data:'.$file->getMimeType().';base64,'.base64_encode(
            file_get_contents($file->getRealPath())
        );
    }
}
