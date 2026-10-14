<?php

/*
 * Vercel serverless entrypoint for Laravel (demo/ephemeral mode).
 *
 * Vercel's filesystem is read-only except /tmp, and there is no
 * persistent SQLite disk. On cold start we:
 *   1. Redirect writable paths (views, sessions, cache) to /tmp.
 *   2. Create /tmp/database.sqlite on first boot, then migrate + seed it.
 *   3. Hand the request to public/index.php.
 *
 * Env vars expected on Vercel (see vercel.json):
 *   APP_ENV=production, APP_DEBUG=false,
 *   LOG_CHANNEL=stderr, SESSION_DRIVER=cookie,
 *   CACHE_STORE=array, QUEUE_CONNECTION=sync,
 *   DB_CONNECTION=sqlite, DB_DATABASE=/tmp/database.sqlite,
 *   VIEW_COMPILED_PATH=/tmp/views,
 *   APP_MAINTENANCE_DRIVER=array
 */

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

foreach (['/tmp/views', '/tmp/sessions', '/tmp/cache', '/tmp/storage'] as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Boot the app once so we can run migrations on a fresh /tmp database.
require __DIR__.'/../vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(ConsoleKernel::class);
$kernel->bootstrap();

$dbPath = (string) env('DB_DATABASE', '/tmp/database.sqlite');

try {
    if (env('DB_CONNECTION', 'sqlite') === 'sqlite' && str_starts_with($dbPath, '/tmp')) {
        if (! file_exists($dbPath)) {
            @touch($dbPath);

            // Fresh ephemeral DB: migrate then seed demo content + admin user.
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
        }
    }
} catch (Throwable $e) {
    // Never break the request because seeding failed; the app will
    // surface the real DB error instead of a boot crash.
    error_log('[vercel-boot] DB init failed: '.$e->getMessage());
}

// Hand over to the standard Laravel HTTP entrypoint.
$request = Illuminate\Http\Request::capture();
$app->handleRequest($request);
