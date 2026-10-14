<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $profile = Profile::current();

        $skills = Skill::orderBy('sort_order')->orderBy('name')->get()->groupBy('category');

        $projects = Project::where('is_published', true)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $experiences = Experience::orderBy('sort_order')->orderByDesc('id')->get();

        $categories = $projects->pluck('category')->unique()->values();

        $projectData = $projects->map(fn ($p) => [
            'id' => $p->id,
            'title' => $p->title,
            'category' => $p->category,
            'summary' => $p->summary,
            'description' => $p->description ?: $p->summary,
            'tech' => $p->tech_list,
            'image' => $p->image_url,
            'demo' => $p->demo_url,
            'repo' => $p->repo_url,
        ])->values();

        return view('home', compact('profile', 'skills', 'projects', 'experiences', 'categories', 'projectData'));
    }

    public function contact(Request $request)
    {
        // Honeypot: bot biasanya mengisi kolom tersembunyi ini.
        if ($request->filled('website')) {
            return response()->json(['message' => 'Terkirim.']);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['nullable', 'string', 'max:150'],
            'body' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email belum benar.',
            'body.required' => 'Pesan wajib diisi.',
            'body.min' => 'Pesan minimal 10 karakter.',
        ]);

        Message::create($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pesan terkirim. Terima kasih, saya akan segera membalas!']);
        }

        return back()->with('success', 'Pesan terkirim. Terima kasih, saya akan segera membalas!');
    }
}
