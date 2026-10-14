@extends('layouts.app')

@section('content')
@php
    $topSkills = $skills->flatten()->sortByDesc('level')->take(4)->pluck('name')->all();
    $codeText = "const dev = {\n"
        . "  nama: " . json_encode($profile->name, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . ",\n"
        . "  peran: " . json_encode($profile->title, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . ",\n"
        . "  stack: " . json_encode($topSkills, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . ",\n"
        . ($profile->location ? "  lokasi: " . json_encode($profile->location, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . ",\n" : "")
        . "  siapKolaborasi: true,\n};";
@endphp

<nav class="nav" id="nav" aria-label="Navigasi utama">
    <div class="nav-inner">
        <a href="#beranda" class="brand">{{ $profile->name }}</a>
        <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="navLinks" aria-label="Buka menu">
            <span></span><span></span>
        </button>
        <ul class="nav-links" id="navLinks">
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#keahlian">Keahlian</a></li>
            <li><a href="#proyek">Proyek</a></li>
            <li><a href="#perjalanan">Perjalanan</a></li>
            <li><a href="#kontak" class="nav-cta">Kontak</a></li>
        </ul>
    </div>
</nav>

<main id="konten">
{{-- ================= HERO ================= --}}
<header class="hero" id="beranda">
    <div class="blob blob-a" data-depth="28" aria-hidden="true"></div>
    <div class="blob blob-b" data-depth="-22" aria-hidden="true"></div>
    <div class="wrap hero-grid">
        <div class="hero-copy">
            <p class="hero-hello">Halo, saya {{ $profile->name }}.</p>
            <h1>{{ $profile->tagline }}</h1>
            <p class="hero-role">
                <span id="role" data-roles="{{ json_encode($profile->roles) }}">{{ $profile->roles[0] }}</span><span class="caret" aria-hidden="true"></span>
            </p>
            <div class="hero-cta">
                <a class="btn btn-primary" href="#proyek">Lihat proyek</a>
                <a class="btn btn-soft" href="#kontak">Ajak kerja sama</a>
                @if($profile->cv_url)
                    <a class="btn btn-ghost" href="{{ $profile->cv_url }}" target="_blank" rel="noopener">Unduh CV</a>
                @endif
            </div>
        </div>

        <figure class="code-window" aria-label="Ringkasan profil dalam bentuk kode">
            <div class="code-bar" aria-hidden="true"><i></i><i></i><i></i><span>profil.js</span></div>
            <pre><code id="code" data-code="{{ $codeText }}">{{ $codeText }}</code></pre>
        </figure>
    </div>
</header>

{{-- ================= TENTANG ================= --}}
<section class="section section-white" id="tentang">
    <div class="wrap about-grid">
        <div class="about-photo">
            @if($profile->photo_url)
                <img src="{{ $profile->photo_url }}" alt="Foto {{ $profile->name }}" loading="lazy">
            @else
                <div class="photo-fallback" aria-hidden="true">{{ \Illuminate\Support\Str::of($profile->name)->substr(0, 1) }}</div>
            @endif
        </div>
        <div class="about-text">
            <h2>Sedikit tentang saya</h2>
            @foreach(preg_split("/\n{2,}/", trim((string) $profile->about)) as $para)
                @if($para !== '') <p>{{ $para }}</p> @endif
            @endforeach

            <dl class="stats">
                <div><dt>Tahun pengalaman</dt><dd>{{ $profile->years_experience }}</dd></div>
                <div><dt>Proyek dipamerkan</dt><dd>{{ $projects->count() }}</dd></div>
                <div><dt>Teknologi dikuasai</dt><dd>{{ $skills->flatten()->count() }}</dd></div>
            </dl>
        </div>
    </div>
</section>

{{-- ================= KEAHLIAN ================= --}}
<section class="section" id="keahlian">
    <div class="wrap">
        <h2>Alat yang saya pakai</h2>
        <p class="section-sub">Persentase menunjukkan seberapa nyaman saya memakainya untuk proyek nyata.</p>

        @if($skills->isEmpty())
            <p class="empty">Belum ada keahlian. Tambahkan lewat panel admin.</p>
        @else
        <div class="skill-grid">
            @foreach($skills as $category => $items)
                <article class="skill-card">
                    <h3>{{ $category }}</h3>
                    <ul>
                        @foreach($items as $skill)
                            <li>
                                <div class="skill-row"><span>{{ $skill->name }}</span><span>{{ $skill->level }}%</span></div>
                                <div class="bar" role="progressbar" aria-valuenow="{{ $skill->level }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $skill->name }}">
                                    <div class="bar-fill" data-level="{{ $skill->level }}"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- ================= PROYEK ================= --}}
<section class="section section-white" id="proyek">
    <div class="wrap">
        <h2>Proyek pilihan</h2>
        <p class="section-sub">Klik kartu untuk melihat cerita dan teknologi di baliknya.</p>

        @if($projects->isEmpty())
            <p class="empty">Belum ada proyek. Tambahkan lewat panel admin.</p>
        @else
            <div class="filters" role="group" aria-label="Filter kategori proyek">
                <button class="chip is-active" data-filter="all" aria-pressed="true">Semua</button>
                @foreach($categories as $cat)
                    <button class="chip" data-filter="{{ $cat }}" aria-pressed="false">{{ $cat }}</button>
                @endforeach
            </div>

            <div class="project-grid" id="projectGrid">
                @foreach($projects as $p)
                    <button class="card project" data-id="{{ $p->id }}" data-category="{{ $p->category }}" type="button">
                        <span class="cover">
                            @if($p->image_url)
                                <img src="{{ $p->image_url }}" alt="" loading="lazy">
                            @else
                                <span class="cover-fallback" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($p->title, 0, 2)) }}</span>
                            @endif
                            @if($p->is_featured)<span class="badge">Unggulan</span>@endif
                        </span>
                        <span class="card-body">
                            <span class="card-cat">{{ $p->category }}</span>
                            <strong class="card-title">{{ $p->title }}</strong>
                            <span class="card-sum">{{ $p->summary }}</span>
                            <span class="tags">
                                @foreach(array_slice($p->tech_list, 0, 3) as $t)<span>{{ $t }}</span>@endforeach
                            </span>
                        </span>
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ================= PERJALANAN ================= --}}
<section class="section" id="perjalanan">
    <div class="wrap narrow">
        <h2>Perjalanan saya</h2>
        <p class="section-sub">Pekerjaan dan pendidikan.</p>

        @if($experiences->isEmpty())
            <p class="empty">Belum ada data. Tambahkan lewat panel admin.</p>
        @else
        <ol class="timeline">
            @foreach($experiences as $e)
                <li class="tl-item tl-{{ $e->type }}">
                    <span class="tl-dot" aria-hidden="true"></span>
                    <div class="tl-card">
                        <span class="tl-period">{{ $e->period }}</span>
                        <h3>{{ $e->role }}</h3>
                        <p class="tl-org">{{ $e->organization }} · {{ $e->type === 'work' ? 'Pekerjaan' : 'Pendidikan' }}</p>
                        @if($e->description)<p>{{ $e->description }}</p>@endif
                    </div>
                </li>
            @endforeach
        </ol>
        @endif
    </div>
</section>

{{-- ================= KONTAK ================= --}}
<section class="section section-petal" id="kontak">
    <div class="wrap contact-grid">
        <div>
            <h2>Punya ide? Ayo ngobrol.</h2>
            <p class="section-sub">Lets Connect.</p>
            <ul class="contact-list">
                @if($profile->email)<li><a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></li>@endif
                @if($profile->phone)<li>{{ $profile->phone }}</li>@endif
                @if($profile->location)<li>{{ $profile->location }}</li>@endif
            </ul>
            <div class="socials">
                @foreach(['github' => 'GitHub', 'linkedin' => 'LinkedIn', 'instagram' => 'Instagram'] as $key => $label)
                    @if($profile->$key)<a class="chip" href="{{ $profile->$key }}" target="_blank" rel="noopener">{{ $label }}</a>@endif
                @endforeach
            </div>
        </div>

        <form class="contact-form" id="contactForm" action="{{ route('contact.send') }}" method="POST" novalidate>
            @csrf
            <div class="hp" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
            <label>Nama<input type="text" name="name" required maxlength="100" autocomplete="name"></label>
            <label>Email<input type="email" name="email" required maxlength="150" autocomplete="email"></label>
            <label>Subjek (opsional)<input type="text" name="subject" maxlength="150"></label>
            <label>Pesan<textarea name="body" rows="5" required minlength="10" maxlength="3000"></textarea></label>
            <button class="btn btn-primary" type="submit">Kirim pesan</button>
            <p class="form-status" id="formStatus" role="status" aria-live="polite"></p>
        </form>
    </div>
</section>
</main>

<footer class="footer">
    <div class="wrap">
        <p>© {{ date('Y') }} {{ $profile->name }}. Dibuat dengan Laravel.</p>
        <a href="{{ route('login') }}">Masuk admin</a>
    </div>
</footer>

{{-- Detail proyek --}}
<dialog id="projectModal" class="modal" aria-labelledby="mTitle">
    <button class="modal-close" id="modalClose" aria-label="Tutup">×</button>
    <div class="modal-cover" id="mCover"></div>
    <div class="modal-body">
        <p class="card-cat" id="mCat"></p>
        <h3 id="mTitle"></h3>
        <p class="modal-desc" id="mDesc"></p>
        <div class="tags" id="mTech"></div>
        <div class="modal-actions" id="mLinks"></div>
    </div>
</dialog>

<script>window.PROJECTS = @json($projectData);</script>
@endsection
