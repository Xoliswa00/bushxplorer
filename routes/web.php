<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\HikeBookings;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Booking\BookingConfirmation;
use App\Livewire\Booking\BookingForm;
use App\Livewire\Booking\PaymentUpload;
use App\Livewire\Gallery\GalleryManager;
use App\Livewire\Member\MemberDashboard;
use App\Livewire\Planner\EventPlanner;
use App\Livewire\Planner\PlannerDashboard;
use App\Http\Controllers\PosterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── Welcome ──────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ── Auth ─────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// ── Hikes (public browsing) ───────────────────────────────────────────────────
Route::prefix('hikes')->name('hikes.')->group(function () {
    Route::get('/', fn () => view('hikes.index'))->name('index');
    Route::get('/{hike:slug}', fn (\App\Models\Hike $hike) => view('hikes.show', compact('hike')))->name('show');
});

// ── Booking flow (auth required) ──────────────────────────────────────────────
Route::middleware(['auth'])->prefix('booking')->name('booking.')->group(function () {
    Route::get('/hike/{hike:id}', BookingForm::class)->name('form');
    Route::get('/{bookingRef}/payment', PaymentUpload::class)->name('payment');
    Route::get('/{bookingRef}/confirmation', BookingConfirmation::class)->name('confirmation');
});

// ── Member portal ──────────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', MemberDashboard::class)->name('dashboard');
});

// ── Event Planner ──────────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('planner')->name('planner.')->group(function () {
    Route::get('/',               PlannerDashboard::class)->name('index');
    Route::get('/new',            EventPlanner::class)->name('create');
    Route::get('/{planId}/edit',  EventPlanner::class)->name('edit');
    Route::get('/{planId}/poster',          [PosterController::class, 'show'])->name('poster');
    Route::get('/{planId}/poster/download', [PosterController::class, 'download'])->name('poster.download');
});

// ── Admin ──────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                       AdminDashboard::class)->name('dashboard');
    Route::get('/gallery',                GalleryManager::class)->name('gallery');
    Route::get('/hikes',        fn () => view('admin.hikes'))->name('hikes');
    Route::get('/hikes/{hike}/bookings',  HikeBookings::class)->name('hike.bookings');
});

// Health check endpoint — polled by the Xquisite monitoring dashboard
Route::get('/api/health', function () {
    // DB
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $db = true;
    } catch (\Throwable) {
        $db = false;
    }

    // Storage writable
    try {
        $testFile = storage_path('framework/.health-check');
        file_put_contents($testFile, '1');
        unlink($testFile);
        $storageWritable = true;
    } catch (\Throwable) {
        $storageWritable = false;
    }

    // File cache read/write
    try {
        \Illuminate\Support\Facades\Cache::put('_health', 1, 5);
        $cache = \Illuminate\Support\Facades\Cache::get('_health') === 1;
    } catch (\Throwable) {
        $cache = false;
    }

    // Disk space
    $diskTotal    = disk_total_space(base_path());
    $diskFree     = disk_free_space(base_path());
    $diskFreeMb   = (int) ($diskFree / 1024 / 1024);
    $diskUsedPct  = $diskTotal > 0 ? round((($diskTotal - $diskFree) / $diskTotal) * 100, 1) : null;

    // Failed jobs (only meaningful when using DB queue)
    try {
        $failedJobs = \Illuminate\Support\Facades\DB::table('failed_jobs')->count();
    } catch (\Throwable) {
        $failedJobs = null;
    }

    $critical = !$db || !$storageWritable;

    return response()->json([
        'status'            => $critical ? 'down' : 'up',
        'db'                => $db,
        'storage_writable'  => $storageWritable,
        'cache'             => $cache,
        'disk_free_mb'      => $diskFreeMb,
        'disk_used_percent' => $diskUsedPct,
        'app_key_set'       => !empty(config('app.key')),
        'failed_jobs'       => $failedJobs,
        'timestamp'         => now()->toISOString(),
    ], $critical ? 503 : 200);
})->name('health');
