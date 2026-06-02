<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Utilisateur;
use App\Models\Colis;
use App\Models\Avis;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ColisController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Auth; 

/*
|--------------------------------------------------------------------------
| Routes Publiques (Accessibles sans authentification)
|--------------------------------------------------------------------------
*/

// Redirection de l'URL racine vers la page de suivi / landing page
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

// Routes publiques pour l'authentification des utilisateurs
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Suivi public via token et dépôt d'avis/feedbacks
Route::get('/suivi/{token}', [ColisController::class, 'tracking'])->name('tracking.public');
Route::post('/suivi/{token}/avis', [App\Http\Controllers\AvisController::class, 'store'])->name('avis.store');


/*
|--------------------------------------------------------------------------
| Espace Administration (Protégé par Auth et le rôle Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. Gestion du Tableau de Bord avec Analyseur d'Avis IA intégré
    Route::get('/dashboard', function() {
        $totalColis = \App\Models\Colis::count() ?? 0;
        $livres = \App\Models\Colis::where('statut', 'Livré')->count() ?? 0; 
        $enCours = \App\Models\Colis::where('statut', 'En cours')->count() ?? 0;
        $retournes = \App\Models\Colis::where('statut', 'Retourné')->count() ?? 0;
        $annules = \App\Models\Colis::where('statut', 'Annulé')->count() ?? 0; 

        // Extraction de tous les commentaires de la base de données
        $allFeedbacks = Avis::pluck('commentaire')->toArray() ?? []; 
        $totalAvis = count($allFeedbacks);
        $positifs = 0;
        
        // Scan automatique des avis par mots-clés positifs
        foreach ($allFeedbacks as $feedback) {
            if (preg_match('/(merci|excellent|top|bon|rapide|mzyan)/i', $feedback)) {
                $positifs++;
            }
        }

        // Calcul dynamique du taux de satisfaction de l'IA
        $tauxSatisfaction = $totalAvis > 0 ? round(($positifs / $totalAvis) * 100) : 100;
        
        if ($tauxSatisfaction >= 75) {
            $iaResume = "La majorité des destinataires sont très satisfaits de la rapidité de livraison et du comportement des livreurs. Quelques remarques mineures sur les horaires.";
            $iaStatus = "Excellent";
            $iaColor = "bg-green-500/10 text-green-400 border-green-500/20";
        } elseif ($tauxSatisfaction >= 50) {
            $iaResume = "Attention, retour d'expérience mitigé. Les clients se plaignent de retards légers dans certaines zones.";
            $iaStatus = "Moyen";
            $iaColor = "bg-amber-500/10 text-amber-400 border-amber-500/20";
        } else {
            $iaResume = "Alerte critique: Plusieurs feedbacks négatifs signalent des colis endommagés et un manque de communication.";
            $iaStatus = "Critique";
            $iaColor = "bg-red-500/10 text-red-400 border-red-500/20";
        }

        return view('admin.dashboard', compact(
            'totalColis', 'livres', 'enCours', 'retournes', 'annules',
            'tauxSatisfaction', 'iaResume', 'iaStatus', 'iaColor', 'totalAvis'
        ));
    })->name('dashboard');

    // 2. Gestion des vues d'administration (Rendu direct des fichiers Blade)
    Route::get('/colis', function () {
        $colisList = Colis::orderBy('created_at', 'desc')->get();
        return view('admin.colis', compact('colisList'));
    })->name('colis.index');

    Route::get('/users', function () {
        $utilisateurs = Utilisateur::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('utilisateurs'));
    })->name('users.index');

    Route::get('/livreurs', function () {
        $livreurs = Utilisateur::where('role', 'livreur')->orderBy('created_at', 'desc')->get();
        return view('admin.livreurs', compact('livreurs'));
    })->name('livreurs.index');

    Route::get('/ecommercants', function () {
        $ecommercants = Utilisateur::where('role', 'ecomercant')->orderBy('created_at', 'desc')->get();
        return view('admin.ecommercants', compact('ecommercants'));
    })->name('ecommercants.index');

    Route::get('/affectations', function () {
        $colis = Colis::whereIn('statut', ['enregistre', 'ramasse'])->orderBy('created_at', 'desc')->get();
        $livreurs = Utilisateur::where('role', 'livreur')->get();
        return view('admin.affectations', compact('colis', 'livreurs'));
    })->name('affectations.index');

    // 3. Opérations financières globales de l'administrateur
    Route::get('/finances', function () {
        // Récupération des portefeuilles des e-commerçants et des livreurs
        $wallets = DB::table('wallets')
                     ->join('utilisateurs', 'wallets.ecomercant_id', '=', 'utilisateurs.id')
                     ->where('utilisateurs.role', 'ecomercant')
                     ->select('wallets.*', 'utilisateurs.nom', 'utilisateurs.prenom', 'utilisateurs.email')
                     ->orderBy('wallets.solde', 'desc')
                     ->get();

        $wallets_livreurs = DB::table('wallets')
                              ->join('utilisateurs', 'wallets.ecomercant_id', '=', 'utilisateurs.id')
                              ->where('utilisateurs.role', 'livreur')
                              ->select('wallets.*', 'utilisateurs.nom', 'utilisateurs.prenom', 'utilisateurs.email')
                              ->orderBy('wallets.solde', 'desc')
                              ->get();

        $retraits = DB::table('retraits')
                      ->join('utilisateurs', 'retraits.ecomercant_id', '=', 'utilisateurs.id')
                      ->select('retraits.*', 'utilisateurs.nom', 'utilisateurs.prenom')
                      ->orderBy('retraits.created_at', 'desc')
                      ->get();

        return view('admin.finances', compact('wallets', 'wallets_livreurs', 'retraits'));
    })->name('finances.index');

    // Actions financières (Validation de retrait et Règlement de compte)
    Route::post('/finances/retrait/{id}/valider', function ($id) {
        $retrait = DB::table('retraits')->where('id', $id)->first();
        if ($retrait && $retrait->statut == 'en_attente') {
            $wallet = DB::table('wallets')->where('ecomercant_id', $retrait->ecomercant_id)->first();
            if ($wallet && $wallet->solde >= $retrait->montant) {
                DB::table('wallets')->where('ecomercant_id', $retrait->ecomercant_id)->decrement('solde', $retrait->montant);
                DB::table('retraits')->where('id', $id)->update(['statut' => 'valide', 'updated_at' => now()]);
                return redirect()->back()->with('success', 'Retrait validé avec succès !');
            }
            return redirect()->back()->with('error', 'Solde insuffisant.');
        }
        return redirect()->back()->with('error', 'Erreur survenue.');
    })->name('finances.valider');

    Route::post('/finances/livreur/{id}/regler', function ($id) {
        DB::table('wallets')->where('id', $id)->update(['solde' => 0, 'updated_at' => now()]);
        return redirect()->back()->with('success', 'Le compte du livreur a été réglé ! 💰');
    })->name('finances.regler');

    // 4. Gestion des affectations de colis aux livreurs
    Route::post('/affectations/store', function (Request $request) {
        $request->validate([
            'colis_id' => 'required|exists:colis,id',
            'livreur_id' => 'required|exists:utilisateurs,id',
        ]);

        DB::table('affectations')->insert([
            'colis_id' => $request->colis_id,
            'utilisateur_id' => $request->livreur_id, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Colis::where('id', $request->colis_id)->update(['statut' => 'en_cours']);
        return redirect()->back()->with('success', 'Le colis a été affecté au livreur avec succès!');
    })->name('affectations.store');

    Route::get('/colis/{colis}/affecter', [AffectationController::class, 'create'])->name('colis.affecter');
    Route::post('/colis/{colis}/affecter', [AffectationController::class, 'store'])->name('colis.store.affecter');

    // 5. Gestion avancée des utilisateurs via les contrôleurs dédiés
    Route::get('/utilisateurs', [UserController::class, 'index'])->name('users.index_controller');
    Route::get('/utilisateurs/creer', [UserController::class, 'create'])->name('users.create');
    Route::post('/utilisateurs/store', [UserController::class, 'store'])->name('users.store');
    Route::post('/utilisateurs/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::get('/livreurs-list', [UserController::class, 'livreursInline'])->name('livreurs.inline');
    Route::get('/e-commercants-list', [UserController::class, 'ecomercantsInline'])->name('ecomercants.inline');

    // 6. Système de journalisation et d'audit de sécurité
    Route::get('/audit', function () {
        $logs = Schema::hasTable('audit_logs') ? DB::table('audit_logs')->orderBy('created_at', 'desc')->get() : collect([]);
        return view('admin.logs', compact('logs'));
    })->name('audit.index');
});


/*
|--------------------------------------------------------------------------
| Espace E-commerçant (Protégé par Auth et le rôle Ecomercant)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:ecomercant'])->prefix('ecomercant')->name('ecomercant.')->group(function () {
    
    // Statistiques du Tableau de Bord de l'e-commerçant
    Route::get('/dashboard', function () {
        $ecomercant_id = Auth::id() ?? 2; 
        $totalColis = DB::table('colis')->where('ecomercant_id', $ecomercant_id)->count();
        $livres     = DB::table('colis')->where('ecomercant_id', $ecomercant_id)->where('statut', 'livre')->count();
        $enCours    = DB::table('colis')->where('ecomercant_id', $ecomercant_id)->where('statut', 'en_cours')->count();
        $retourne   = DB::table('colis')->where('ecomercant_id', $ecomercant_id)->where('statut', 'retourne')->count();
        return view('ecomercant.dashboard', compact('totalColis', 'livres', 'enCours', 'retourne'));
    })->name('dashboard');

    // Suivi du portefeuille financier et historique des retraits
    Route::get('/finances', function () {
        $ecomercant_id = Auth::id() ?? 2; 
        $wallet = DB::table('wallets')->where('ecomercant_id', $ecomercant_id)->first();
        $retraits = DB::table('retraits')->where('ecomercant_id', $ecomercant_id)->orderBy('created_at', 'desc')->get();
        return view('ecomercant.finances', compact('wallet', 'retraits'));
    })->name('finances');

    // Enregistrement d'une nouvelle demande de retrait
    Route::post('/finances/retrait/store', function (Request $request) {
        $ecomercant_id = Auth::id() ?? 2; 
        $request->validate(['montant' => 'required|numeric|min:10']);

        $wallet = DB::table('wallets')->where('ecomercant_id', $ecomercant_id)->first();
        if (!$wallet || $wallet->solde < $request->montant) {
            return redirect()->back()->with('error', 'Votre solde est insuffisant pour effectuer ce retrait ! ❌');
        }

        DB::table('retraits')->insert([
            'ecomercant_id' => $ecomercant_id,
            'montant' => $request->montant,
            'statut' => 'en_attente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Votre demande de retrait a été envoyée avec succès à l\'administrateur ! 🚀');
    })->name('retrait.store');

    // Gestion de la logistique des colis
    Route::get('/colis', [ColisController::class, 'mesColis'])->name('colis.index');
    Route::get('/colis/creer', [ColisController::class, 'create'])->name('colis.create');
    Route::post('/colis', [ColisController::class, 'store'])->name('colis.store');
    Route::get('/colis/{colis}', [ColisController::class, 'show'])->name('colis.show');
});


/*
|--------------------------------------------------------------------------
| Espace Livreur (Protégé par Auth et le rôle Livreur)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:livreur'])->prefix('livreur')->name('livreur.')->group(function () {
    Route::get('/dashboard', fn() => view('livreur.dashboard'))->name('dashboard');
    Route::put('/colis/{colis}/statut', [ColisController::class, 'changerStatut'])->name('colis.statut');
    Route::get('/mes-affectations', [AffectationController::class, 'mesAffectations'])->name('affectations.index'); 
});