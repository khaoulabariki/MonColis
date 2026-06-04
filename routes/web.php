<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Utilisateur;
use App\Models\Colis;
use App\Models\Avis;
use App\Models\Affectation;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ColisController;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\RetraitController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Routes Publiques (Accessibles sans authentification)
|--------------------------------------------------------------------------
*/

// Redirection de l'URL racine vers la page de suivi public
Route::get('/', function () {
    return redirect('/tracking');
});

// Page d'accueil principale et système de suivi public des colis
Route::get('/tracking', function (Request $request) {
    $code = $request->query('code');
    $colis = null;

    if ($code) {
        $colis = DB::table('colis')->where('code_suivi', $code)->first();
    }

    return view('welcome', compact('colis', 'code'));
});

// Routes de gestion de l'authentification (Affichage, Connexion, Déconnexion)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Suivi public via un jeton unique et dépôt d'avis clients
Route::get('/suivi/{token}', [ColisController::class, 'tracking'])->name('tracking.public');
Route::post('/suivi/{token}/avis', [AvisController::class, 'store'])->name('avis.store');

/*
|--------------------------------------------------------------------------
| Espace Administration (Protégé par Auth et le rôle Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. Tableau de bord principal avec Analyseur d'Avis IA intégré
    Route::get('/dashboard', function() {
        $totalColis = Colis::count() ?? 0;
        $livres = Colis::where('statut', 'Livré')->count() ?? 0; 
        $enCours = Colis::where('statut', 'En cours')->count() ?? 0;
        $retournes = Colis::where('statut', 'Retourné')->count() ?? 0;
        $annules = Colis::where('statut', 'Annulé')->count() ?? 0; 

        $allFeedbacks = Avis::pluck('commentaire')->toArray() ?? []; 
        $totalAvis = count($allFeedbacks);
        $positifs = 0;
        
        foreach ($allFeedbacks as $feedback) {
            if (preg_match('/(merci|excellent|top|bon|rapide|mzyan)/i', $feedback)) {
                $positifs++;
            }
        }

        $tauxSatisfaction = $totalAvis > 0 ? round(($positifs / $totalAvis) * 100) : 100;
        
        if ($tauxSatisfaction >= 75) {
            $iaResume = "La majorité des destinataires sont très satisfaits de la rapidité de livraison et du comportement des livreurs.";
            $iaStatus = "Excellent";
            $iaColor = "bg-green-500/10 text-green-400 border-green-500/20";
        } elseif ($tauxSatisfaction >= 50) {
            $iaResume = "Attention, retour d'expérience mitigé. Les clients se plaignent de retards légers.";
            $iaStatus = "Moyen";
            $iaColor = "bg-amber-500/10 text-amber-400 border-amber-500/20";
        } else {
            $iaResume = "Alerte critique: Plusieurs feedbacks négatifs signalent des colis endommagés.";
            $iaStatus = "Critique";
            $iaColor = "bg-red-500/10 text-red-400 border-red-500/20";
        }

        return view('admin.dashboard', compact(
            'totalColis', 'livres', 'enCours', 'retournes', 'annules',
            'tauxSatisfaction', 'iaResume', 'iaStatus', 'iaColor', 'totalAvis'
        ));
    })->name('dashboard');

    // 2. Affichage de la liste globale des colis
    Route::get('/colis', function () {
        $colisList = Colis::with('livreur')->orderBy('created_at', 'desc')->get();
        $ecommercants = collect([]); 
        $mode = 'liste';
        $colisEnCours = null;

        return view('admin.colis.index', compact('colisList', 'ecommercants', 'mode', 'colisEnCours'));
    })->name('colis.index');

    // 3. Formulaire de création d'un nouveau colis
    Route::get('/colis/creer', function () {
        $colisList = Colis::orderBy('created_at', 'desc')->get();
        $ecommercants = Utilisateur::where('role', 'ecommercant')->get();
        $mode = 'creation'; 
        $colisEnCours = null;

        return view('admin.colis.index', compact('colisList', 'ecommercants', 'mode', 'colisEnCours'));
    })->name('colis.create');

    // 4. Gestion des opérations financières (Traitée par TransactionController et RetraitController)
    Route::get('/finances', [TransactionController::class, 'index'])->name('finances.index');
    Route::post('/finances/retrait/{id}/valider', [RetraitController::class, 'traiterRetrait'])->name('finances.valider');

    // 5. GESTION DES UTILISATEURS
    
    // --- SECTION : ADMINISTRATEURS ---
    Route::get('/administrateurs', function() {
        $adminsList = Utilisateur::where('role', 'admin')->get();
        return view('admin.administrateurs.index', compact('adminsList'));
    })->name('administrateurs.index');

    Route::post('/administrateurs/store', function (Request $request) {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|min:6',
        ]);
        Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'admin',
            'statut' => true
        ]);
        return redirect()->route('admin.administrateurs.index')->with('success', 'Administrateur ajouté avec succès !');
    })->name('administrateurs.store');

    Route::delete('/administrateurs/{id}/supprimer', function ($id) {
        Utilisateur::where('id', $id)->delete();
        return redirect()->route('admin.administrateurs.index')->with('success', 'Administrateur supprimé avec succès !');
    })->name('administrateurs.destroy');


    // --- SECTION : LIVREURS ---
    Route::get('/livreurs', function() {
        $livreursList = Utilisateur::where('role', 'livreur')->get();
        return view('admin.livreurs.index', compact('livreursList'));
    })->name('livreurs.index');

    Route::post('/livreurs/store', function (Request $request) {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|min:6',
        ]);

        Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'livreur',
            'telephone' => $request->telephone ?? null,
            'statut' => true
        ]);
        return redirect()->route('admin.livreurs.index')->with('success', 'Livreur ajouté avec succès !');
    })->name('livreurs.store');

    Route::delete('/livreurs/{id}/supprimer', function ($id) {
        Utilisateur::where('id', $id)->delete();
        return redirect()->route('admin.livreurs.index')->with('success', 'Livreur supprimé avec succès !');
    })->name('livreurs.destroy');


    // --- SECTION : E-COMMERÇANTS ---
    Route::get('/ecommercants', function() {
        $ecommercantsList = Utilisateur::where('role', 'ecommercant')->get();
        return view('admin.ecommercants.index', compact('ecommercantsList'));
    })->name('ecommercants.index');

    Route::post('/ecommercants/store', function (Request $request) {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|min:6',
        ]);

        Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'ecommercant',
            'telephone' => $request->telephone ?? null,
            'statut' => true
        ]);
        return redirect()->route('admin.ecommercants.index')->with('success', 'E-commerçant ajouté avec succès !');
    })->name('ecommercants.store');

    Route::delete('/ecommercants/{id}/supprimer', function ($id) {
        Utilisateur::where('id', $id)->delete();
        return redirect()->route('admin.ecommercants.index')->with('success', 'E-commerçant supprimé avec succès !');
    })->name('ecommercants.destroy');

    
    // 6. Gestion et suivi des affectations de colis aux livreurs
    Route::get('/affectations', [AffectationController::class, 'index'])->name('affectations.index');
    
    // Traitement et liaison de l'action de soumission d'une affectation
    Route::post('/affectations/{id}', [AffectationController::class, 'store'])->name('affectations.store');

    // 7. Journaux et logs d'audit du système informatique
    Route::get('/audit', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit.index');
});

/*
|--------------------------------------------------------------------------
| Routes Autonomes de Gestion des Colis (Espace Admin)
|--------------------------------------------------------------------------
*/

// Formulaire de modification de structure d'un colis spécifique
Route::get('/admin/colis/{id}/modifier', function ($id) {
    $colisList = Colis::orderBy('created_at', 'desc')->get();
    $ecommercants = Utilisateur::where('role', 'ecommercant')->get();
    $colisEnCours = Colis::find($id);
    $mode = 'creation'; 

    return view('admin.colis.index', compact('colisList', 'ecommercants', 'mode', 'colisEnCours'));
})->name('admin.colis.edit');

// Enregistrement unifié ou mise à jour complète d'un colis
Route::post('/admin/colis/store', function (Illuminate\Http\Request $request) {
    $request->validate([
        'code_barre'          => 'required|string',
        'destinataire'        => 'required|string|max:255',
        'prenom_destinataire' => 'required|string|max:255',
        'telephone'           => 'required|string',
        'ville'               => 'required|string',
        'prix'                => 'required|numeric',
        'ecommercant_id'      => 'required|exists:utilisateurs,id',
    ]);

    $colisExistant = \App\Models\Colis::where('code_suivi', $request->code_barre)->first();

    if ($colisExistant) {
        $colisExistant->update([
            'nom_destinataire'       => $request->destinataire,
            'prenom_destinataire'    => $request->prenom_destinataire,
            'telephone_destinataire' => $request->telephone,
            'adresse_destinataire'   => $request->ville, 
            'prix'                   => $request->prix,
            'ecommercant_id'         => $request->ecommercant_id,
        ]);
        $message = 'Colis mis à jour avec succès !';
    } else {
        \App\Models\Colis::create([
            'code_suivi'             => $request->code_barre,
            'nom_destinataire'       => $request->destinataire,
            'prenom_destinataire'    => $request->prenom_destinataire,
            'telephone_destinataire' => $request->telephone,
            'adresse_destinataire'   => $request->ville, 
            'poids'                  => 1.0, 
            'prix'                   => $request->prix,
            'statut'                 => 'enregistre', 
            'ecommercant_id'         => $request->ecommercant_id,
            'token_suivi'            => (string) \Illuminate\Support\Str::uuid(), 
        ]);
        $message = 'Colis enregistré avec succès !';
    }

    return redirect()->route('admin.colis.index')->with('success', $message);
})->name('admin.colis.store');

// Suppression définitive d'un colis de la base de données
Route::delete('/admin/colis/{id}/supprimer', function ($id) {
    Colis::where('id', $id)->delete();
    return redirect()->route('admin.colis.index')->with('success', 'Colis supprimé avec succès !');
})->name('admin.colis.destroy');

/*
|--------------------------------------------------------------------------
| Espace E-commerçant (Protégé par Auth et le rôle ecommercant)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:ecommercant'])->prefix('ecommercant')->name('ecommercant.')->group(function () {
    
    // 1. Tableau de bord dynamique de l'E-commerçant connecté avec calcul des statistiques
    Route::get('/dashboard', function () {
        $totalColis = Colis::where('ecommercant_id', auth()->id())->count();
        $livres = Colis::where('ecommercant_id', auth()->id())->where('statut', 'Livré')->count();
        $enCours = Colis::where('ecommercant_id', auth()->id())->where('statut', 'En cours')->count();
        // Correction de la variable pour correspondre exactement à dashboard.blade.php
        $retournes = Colis::where('ecommercant_id', auth()->id())->where('statut', 'Retourné')->count();

        return view('ecommercant.dashboard', compact('totalColis', 'livres', 'enCours', 'retournes'));
    })->name('dashboard');

    // 2. Consultation et détails du portefeuille financier de l'E-commerçant via le WalletController
    Route::get('/finances', [WalletController::class, 'getWalletDetails'])->name('finances');

    // 3. Affichage de la liste filtrée des colis de l'E-commerçant connecté
    Route::get('/colis', function () {
        $colis = \App\Models\Colis::where('ecommercant_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('ecommercant.colis.index', compact('colis'));
    })->name('colis.index');
    
    Route::post('/colis', [\App\Http\Controllers\ColisController::class, 'store'])->name('colis.store');

    // 4. Affichage du formulaire de création de colis pour l'E-commerçant
    Route::get('/colis/creer', function () {
        return view('ecommercant.colis.create');
    })->name('colis.create');
});

/*
|--------------------------------------------------------------------------
| Espace Livreur (Protégé par Auth et le rôle livreur)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:livreur'])->prefix('livreur')->name('livreur.')->group(function () {
    
    // Tableau de bord du livreur avec compte des colis assignés
    Route::get('/dashboard', function () {
        $colisCount = Affectation::where('livreur_id', auth()->id())
            ->whereIn('statut', ['en_attente', 'en_cours'])
            ->count();
        
        return view('livreur.dashboard', compact('colisCount')); 
    })->name('dashboard');

    // Liste complète des colis assignés au livreur actuellement connecté
    Route::get('/mes-livraisons', function () {
        $affectations = Affectation::with('colis')
            ->where('livreur_id', auth()->id())
            ->latest()
            ->get();
            
        return view('livreur.colis.index', compact('affectations')); 
    })->name('mes_livraisons');
});

/*
|--------------------------------------------------------------------------
| Route Temporaire de Génération / Réinitialisation du Compte de Test
|--------------------------------------------------------------------------
*/
Route::get('/create-ecom', function () {
    
    $oldUser = \App\Models\Utilisateur::where('email', 'ecom@nwsallik.com')->first();
    if ($oldUser) {
        \App\Models\Wallet::where('ecommercant_id', $oldUser->id)->delete();
        $oldUser->delete();
    }
    
    $user = \App\Models\Utilisateur::create([
        'nom' => 'Nwsallik',
        'prenom' => 'Ecom',
        'email' => 'ecom@nwsallik.com',
        'password' => bcrypt('password123'),
        'role' => 'ecommercant',
        'statut' => true 
    ]);

    
    \App\Models\Wallet::create([
        'ecommercant_id' => $user->id,
        'solde' => 0.00
    ]);
    
    return "Compte de test E-commerçant créé avec succès ! ";
});