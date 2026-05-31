<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ColisController;
use Illuminate\Support\Facades\Route;

// ── Auth ──
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Tracking public ──
Route::get('/suivi/{token}', [ColisController::class, 'tracking'])->name('tracking');
// ── Avis public ──
Route::post('/suivi/{token}/avis', [App\Http\Controllers\AvisController::class, 'store'])->name('avis.store');
// ── Admin ──
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/colis', [ColisController::class, 'index'])->name('colis.index');
});

// ── E-commerçant ──
Route::middleware(['auth', 'role:ecomercant'])->prefix('ecomercant')->name('ecomercant.')->group(function () {
    Route::get('/dashboard', fn() => view('ecomercant.dashboard'))->name('dashboard');
    Route::get('/colis', [ColisController::class, 'mesColis'])->name('colis.index');
    Route::get('/colis/creer', [ColisController::class, 'create'])->name('colis.create');
    Route::post('/colis', [ColisController::class, 'store'])->name('colis.store');
    Route::get('/colis/{colis}', [ColisController::class, 'show'])->name('colis.show');
});

// ── Livreur ──
Route::middleware(['auth', 'role:livreur'])->prefix('livreur')->name('livreur.')->group(function () {
    Route::get('/dashboard', fn() => view('livreur.dashboard'))->name('dashboard');
    Route::put('/colis/{colis}/statut', [ColisController::class, 'changerStatut'])->name('colis.statut');
});