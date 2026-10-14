<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {
        return view('admin.experiences.index', [
            'experiences' => Experience::orderBy('sort_order')->orderByDesc('id')->get(),
            'experience' => new Experience(['type' => 'work']),
        ]);
    }

    public function store(Request $request)
    {
        Experience::create($this->validated($request));

        return back()->with('success', 'Pengalaman ditambahkan.');
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(Request $request, Experience $experience)
    {
        $experience->update($this->validated($request));

        return redirect()->route('admin.experiences.index')->with('success', 'Pengalaman diperbarui.');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();

        return back()->with('success', 'Pengalaman dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'type' => ['required', 'in:work,education'],
            'role' => ['required', 'string', 'max:120'],
            'organization' => ['required', 'string', 'max:120'],
            'period' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:1500'],
            'sort_order' => ['nullable', 'integer'],
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
