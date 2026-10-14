<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Proyek', 'value' => Project::count(), 'route' => 'admin.projects.index'],
                ['label' => 'Keahlian', 'value' => Skill::count(), 'route' => 'admin.skills.index'],
                ['label' => 'Pengalaman', 'value' => Experience::count(), 'route' => 'admin.experiences.index'],
                ['label' => 'Pesan belum dibaca', 'value' => Message::where('is_read', false)->count(), 'route' => 'admin.messages.index'],
            ],
            'latestMessages' => Message::latest()->limit(5)->get(),
        ]);
    }
}
