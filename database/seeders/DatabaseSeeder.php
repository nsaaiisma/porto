<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin. Ganti lewat .env (ADMIN_EMAIL / ADMIN_PASSWORD) atau lewat menu Profil setelah login.
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@portofolio.test')],
            ['name' => 'Admin', 'password' => Hash::make(env('ADMIN_PASSWORD', 'password123'))]
        );

        Profile::firstOrCreate([], [
            'name' => 'Alya Rahma',
            'title' => 'Full-stack Laravel Developer',
            'typing_roles' => 'Full-stack Laravel Developer, Pecinta UI yang rapi, Pemecah masalah backend',
            'tagline' => 'Membangun aplikasi web yang rapi, cepat, dan menyenangkan dipakai.',
            'about' => "Saya developer yang suka mengubah kebutuhan bisnis menjadi aplikasi yang jelas dan mudah dirawat. Sehari-hari saya bekerja dengan Laravel, MySQL, dan JavaScript modern.\n\nSaya percaya kode yang baik itu mudah dibaca orang lain, dan antarmuka yang baik itu tidak perlu dijelaskan. Ganti teks ini lewat menu Profil di panel admin.",
            'email' => 'halo@contoh.com',
            'phone' => '+62 812 0000 0000',
            'location' => 'Bogor, Jawa Barat',
            'github' => 'https://github.com/',
            'linkedin' => 'https://linkedin.com/',
            'years_experience' => 3,
        ]);

        if (Skill::count() === 0) {
            $skills = [
                ['Laravel', 'Backend', 90], ['PHP', 'Backend', 88], ['MySQL', 'Backend', 82], ['REST API', 'Backend', 85],
                ['JavaScript', 'Frontend', 80], ['Tailwind CSS', 'Frontend', 78], ['Vue.js', 'Frontend', 65],
                ['Git & GitHub', 'Tools', 85], ['Docker', 'Tools', 60], ['Figma', 'Tools', 70],
            ];
            foreach ($skills as $i => [$name, $cat, $lvl]) {
                Skill::create(['name' => $name, 'category' => $cat, 'level' => $lvl, 'sort_order' => $i]);
            }
        }

        if (Experience::count() === 0) {
            Experience::insert([
                ['type' => 'work', 'role' => 'Backend Developer', 'organization' => 'PT Kreasi Digital', 'period' => '2024 – Sekarang', 'description' => 'Membangun dan merawat API untuk aplikasi pemesanan. Mengurangi waktu respons halaman utama sekitar 40% lewat optimasi query dan cache.', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['type' => 'work', 'role' => 'Junior Web Developer', 'organization' => 'Studio Bunga', 'period' => '2022 – 2024', 'description' => 'Mengerjakan website company profile dan sistem admin untuk klien UMKM dengan Laravel.', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['type' => 'education', 'role' => 'S1 Teknik Informatika', 'organization' => 'Universitas Contoh', 'period' => '2018 – 2022', 'description' => 'Fokus pada rekayasa perangkat lunak dan basis data.', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (Project::count() === 0) {
            $projects = [
                ['Sistem Kasir Kafe', 'Web', 'Aplikasi kasir dengan laporan harian dan stok bahan.', "Aplikasi kasir untuk kafe kecil: pencatatan pesanan, pembayaran tunai/QRIS, dan laporan penjualan harian.\n\nAdmin dapat mengatur menu, harga, dan stok bahan. Laporan bisa diunduh sebagai PDF.", 'Laravel, MySQL, Alpine.js', true],
                ['API Reservasi Klinik', 'API', 'REST API jadwal dokter dan reservasi pasien.', "REST API dengan autentikasi token, jadwal dokter, dan notifikasi pengingat reservasi lewat email.\n\nDilengkapi dokumentasi OpenAPI dan test otomatis.", 'Laravel, Sanctum, PHPUnit', true],
                ['Aplikasi Catatan Belajar', 'Mobile', 'Aplikasi catatan dengan tag dan pengingat belajar.', "Aplikasi catatan belajar dengan tag, pencarian cepat, dan pengingat harian.", 'Flutter, Laravel API', false],
                ['Redesain Toko Online', 'UI/UX', 'Redesain alur checkout agar lebih singkat.', "Merombak alur checkout dari 5 langkah menjadi 3 langkah berdasarkan hasil uji pengguna.", 'Figma, Tailwind CSS', false],
            ];
            foreach ($projects as $i => [$title, $cat, $sum, $desc, $tech, $feat]) {
                Project::create([
                    'title' => $title, 'slug' => \Illuminate\Support\Str::slug($title), 'category' => $cat,
                    'summary' => $sum, 'description' => $desc, 'tech_stack' => $tech,
                    'is_featured' => $feat, 'is_published' => true, 'sort_order' => $i,
                    'demo_url' => 'https://example.com', 'repo_url' => 'https://github.com/',
                ]);
            }
        }
    }
}
