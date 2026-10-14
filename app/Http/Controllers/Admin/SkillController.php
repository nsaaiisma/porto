<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        return view('admin.skills.index', [
            'skills' => Skill::orderBy('category')->orderBy('sort_order')->orderBy('name')->get(),
            'skill' => new Skill(['level' => 80]),
            'categories' => Skill::distinct()->pluck('category'),
        ]);
    }

    public function store(Request $request)
    {
        Skill::create($this->validated($request));

        return back()->with('success', 'Keahlian ditambahkan.');
    }

    public function edit(Skill $skill)
    {
        return view('admin.skills.edit', [
            'skill' => $skill,
            'categories' => Skill::distinct()->pluck('category'),
        ]);
    }

    public function update(Request $request, Skill $skill)
    {
        $skill->update($this->validated($request));

        return redirect()->route('admin.skills.index')->with('success', 'Keahlian diperbarui.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return back()->with('success', 'Keahlian dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'category' => ['required', 'string', 'max:50'],
            'level' => ['required', 'integer', 'min:0', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
