<?php

use App\Http\Controllers\Siswa\AuthController;
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Siswa\KuisController;
use App\Http\Controllers\Siswa\LeaderboardController;
use App\Http\Controllers\Siswa\MateriController;
use App\Http\Controllers\Siswa\ProfilController;
use Illuminate\Support\Facades\Route;

// Storage route
Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path('app/public/' . $folder . '/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    $file = file_get_contents($path);
    $type = mime_content_type($path);
    
    return response($file, 200)->header('Content-Type', $type);
})->where('folder', '.*')->where('filename', '.*');

// ─── Auth Siswa ──────────────────────────────────────────────────────────────
Route::prefix('siswa')->name('siswa.')->group(function () {

    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // ─── Protected ───────────────────────────────────────────────────────────
    Route::middleware('siswa.auth')->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Kuis
        Route::prefix('kuis')->name('kuis.')->group(function () {
            Route::get('/',                          [KuisController::class, 'index'])->name('index');
            Route::get('masuk',                      [KuisController::class, 'formMasuk'])->name('masuk');
            Route::post('masuk',                     [KuisController::class, 'prosesKode'])->name('masuk.post');
            Route::post('{kodeKuis}/mulai',          [KuisController::class, 'mulai'])->name('mulai');
            Route::get('{kodeKuis}/kerjakan/{nomor}',[KuisController::class, 'kerjakan'])->name('kerjakan');
            Route::post('{kodeKuis}/jawab/{nomor}',  [KuisController::class, 'jawab'])->name('jawab');
            Route::post('{kodeKuis}/selesai',        [KuisController::class, 'selesai'])->name('selesai');
            Route::get('{kodeKuis}/hasil',           [KuisController::class, 'hasil'])->name('hasil');
        });

        // Materi
        Route::prefix('materi')->name('materi.')->group(function () {
            Route::get('/',          [MateriController::class, 'index'])->name('index');
            Route::get('{materi}',   [MateriController::class, 'show'])->name('show');
            Route::post('{materi}/klaim', [MateriController::class, 'klaim'])->name('klaim');
        });

        Route::get('leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
        Route::get('profil',      [ProfilController::class, 'index'])->name('profil');
    });
});

// Redirect root ke login siswa
Route::redirect('/', '/siswa/login');
