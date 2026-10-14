<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// ---- Halaman publik ----
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/kontak', [HomeController::class, 'contact'])
    ->middleware('throttle:5,1')
    ->name('contact.send');

// ---- Autentikasi admin ----
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])
    ->middleware('throttle:10,1')
    ->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');

// ---- Panel admin ----
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profil/password', [ProfileController::class, 'password'])->name('profile.password');

    Route::resource('projects', ProjectController::class)->except('show');
    Route::resource('skills', SkillController::class)->except(['show', 'create']);
    Route::resource('experiences', ExperienceController::class)->except(['show', 'create']);

    Route::get('pesan', [MessageController::class, 'index'])->name('messages.index');
    Route::get('pesan/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::delete('pesan/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
});
