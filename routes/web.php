<?php

use App\Http\Controllers\AffectationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ColisController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

// ── Auth ──
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Tracking public ──
Route::get('/suivi/{token}', [ColisController::class, 'tracking'])->name('tracking');
Route::post('/suivi/{token}/avis', [App\Http\Controllers\AvisController::class, 'store'])->name('avis.store');

// ── Admin ──
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    
    // Routes Colis d l-admin (Directement la vue pour l'instant)
    Route::get('/colis', function() {
        return view('admin.colis.index');
    })->name('colis.index');
    
    Route::get('/colis/{colis}/affecter', [AffectationController::class, 'create'])->name('colis.affecter');
    Route::post('/colis/{colis}/affecter', [AffectationController::class, 'store'])->name('colis.store.affecter');

    // Routes Utilisateurs 
    Route::get('/utilisateurs', [UserController::class, 'index'])->name('users.index');
    Route::get('/utilisateurs/creer', [UserController::class, 'create'])->name('users.create');
    Route::post('/utilisateurs/store', [UserController::class, 'store'])->name('users.store');
    Route::post('/utilisateurs/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::get('/livreurs', [UserController::class, 'livreursInline'])->name('livreurs.index');
    Route::get('/e-commercants', [UserController::class, 'ecomercantsInline'])->name('ecomercants.index');

    // Route de la page Affectations
    Route::get('/affectations', function() {
        return view('admin.affectations.index');
    })->name('affectations.index');

    // Route de la page Finances
    Route::get('/finances', function() {
        return view('admin.finances.index');
    })->name('finances.index');

    // Route de la page Audit Log
    Route::get('/audit-log', function() {
        return view('admin.audit.index');
    })->name('audit.index');
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
    Route::get('/mes-affectations', [AffectationController::class, 'mesAffectations'])->name('affectations.index');
});