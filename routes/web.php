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
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\RetraitController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Routes Publiques (Accessibles sans authentification)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/tracking');
});

Route::get('/tracking', function (Request $request) {
    $code = $request->query('code');
    $colis = null;

    if ($code) {
        $colis = DB::table('colis')->where('code_suivi', $code)->first();
    }

    return view('welcome', compact('colis', 'code'));
});

Route::get('/contact', function () {
    return view('auth.contact');
})->name('contact');

Route::post('/contact', function (Illuminate\Http\Request $request) {
    return redirect()->back()->with('success', 'Votre message a été envoyé avec succès à l\'administration !');
})->name('contact.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/suivi/{token}', [ColisController::class, 'tracking'])->name('tracking.public');
Route::post('/suivi/{token}/avis', [AvisController::class, 'store'])->name('avis.store');

/*
|--------------------------------------------------------------------------
| Espace Administration (Protégé par Auth et le rôle Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
   
    Route::get('/dashboard', function() {
        $totalColis = Colis::count() ?? 0;
        $livres = Colis::where('statut', 'Livré')->count() ?? 0; 
        $enCours = Colis::where('statut', 'En cours')->count() ?? 0;
        $retournes = Colis::where('statut', 'Retourné')->count() ?? 0;
        $annules = Colis::where('statut', 'Annulé')->count() ?? 0; 

        // 🎯 جلب إجمالي السولد ديال كاع الـ Wallets ف السيستيم باش يبان عند الـ Admin
        $totalEcomWallets = \App\Models\Wallet::sum('solde') ?? 0;

        $recentAvis = \App\Models\Avis::with('colis')->orderBy('created_at', 'desc')->get();
        $allFeedbacks = Avis::pluck('commentaire')->toArray() ?? []; 
        $totalAvis = count($allFeedbacks);
        $positifs = 0;
        
        foreach ($allFeedbacks as $feedback) {
            if (preg_match('/(merci|excellent|top|bon|rapide|mzyan)/i', $feedback)) {
                $positifs++;
            }
        }

        $tauxSatisfaction = $totalAvis > 0 ? round(($positifs / $totalAvis) * 100) : 0;
        
        if ($totalAvis == 0) {
            $iaResume = "Aucune donnée disponible pour le moment (0 avis).";
            $iaStatus = "Aucun Avis";
            $iaColor = "bg-gray-500/10 text-gray-400 border-gray-500/20";
        } elseif ($tauxSatisfaction >= 75) {
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
            'tauxSatisfaction', 'iaResume', 'iaStatus', 'iaColor', 'totalAvis', 'recentAvis', 'totalEcomWallets'
        ));
    })->name('dashboard');

    Route::get('/colis', function () {
        $colisList = Colis::with('livreur')->orderBy('created_at', 'desc')->get();
        $ecommercants = collect([]); 
        $mode = 'liste';
        $colisEnCours = null;
        return view('admin.colis.index', compact('colisList', 'ecommercants', 'mode', 'colisEnCours'));
    })->name('colis.index');

    Route::get('/colis/creer', function () {
        $colisList = Colis::orderBy('created_at', 'desc')->get();
        $ecommercants = Utilisateur::where('role', 'ecommercant')->get();
        $mode = 'creation'; 
        $colisEnCours = null;
        return view('admin.colis.index', compact('colisList', 'ecommercants', 'mode', 'colisEnCours'));
    })->name('colis.create');

    Route::get('/finances', [TransactionController::class, 'index'])->name('finances.index');
    
    // 🎯 التصحيح السحري والربط المتناسق $100% مع الـ Form د الـ Blade والـ Controller
    Route::post('/finances/valider/{id}', [RetraitController::class, 'traiterRetrait'])->name('finances.valider');

    // --- SECTIONS UTILISATEURS ---
    Route::get('/administrateurs', [UtilisateurController::class, 'indexAdmin'])->name('administrateurs.index');
    Route::post('/administrateurs/store', [UtilisateurController::class, 'storeAdmin'])->name('administrateurs.store');
    Route::delete('/administrateurs/{id}/supprimer', [UtilisateurController::class, 'destroyAdmin'])->name('administrateurs.destroy');

    Route::get('/livreurs', [UtilisateurController::class, 'indexLivreur'])->name('livreurs.index');
    Route::post('/livreurs/store', [UtilisateurController::class, 'storeLivreur'])->name('livreurs.store');
    Route::delete('/livreurs/{id}/supprimer', [UtilisateurController::class, 'destroyLivreur'])->name('livreurs.destroy');

    Route::get('/ecommercants', [UtilisateurController::class, 'indexEcom'])->name('ecommercants.index');
    Route::post('/ecommercants/store', [UtilisateurController::class, 'storeEcom'])->name('ecommercants.store');
    Route::delete('/ecommercants/{id}/supprimer', [UtilisateurController::class, 'destroyEcom'])->name('ecommercants.destroy');

    Route::get('/affectations', [AffectationController::class, 'index'])->name('affectations.index');
    Route::post('/affectations/{id}', [AffectationController::class, 'store'])->name('affectations.store');

    Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');
});

/*
|--------------------------------------------------------------------------
| Routes Autonomes de Gestion des Colis (Espace Admin)
|--------------------------------------------------------------------------
*/
Route::get('/admin/colis/{id}/modifier', function ($id) {
    $colisList = Colis::orderBy('created_at', 'desc')->get();
    $ecommercants = Utilisateur::where('role', 'ecommercant')->get();
    $colisEnCours = Colis::find($id);
    $mode = 'creation'; 
    return view('admin.colis.index', compact('colisList', 'ecommercants', 'mode', 'colisEnCours'));
})->name('admin.colis.edit');

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

Route::delete('/admin/colis/{id}/supprimer', function ($id) {
    Colis::where('id', $id)->delete();
    return redirect()->route('admin.colis.index')->with('success', 'Colis supprimé avec succès !');
})->name('admin.colis.destroy');

Route::post('/admin/finances/cloturer/{livreur_id}', [TransactionController::class, 'cloturerLivreur'])->name('admin.finances.cloturer');

/*
|--------------------------------------------------------------------------
| Espace E-commerçant (Protégé par Auth et le rôle ecommercant)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:ecommercant'])->prefix('ecommercant')->name('ecommercant.')->group(function () {
    
    Route::get('/dashboard', function () {
        $totalColis = Colis::where('ecommercant_id', auth()->id())->count();
        $livres = Colis::where('ecommercant_id', auth()->id())->where('statut', 'Livré')->count();
        $enCours = Colis::where('ecommercant_id', auth()->id())->where('statut', 'En cours')->count();
        $retournes = Colis::where('ecommercant_id', auth()->id())->where('statut', 'Retourné')->count();

        $recentAvis = \App\Models\Avis::whereHas('colis', function($query) {
            $query->where('ecommercant_id', auth()->id());
        })->with('colis')->orderBy('created_at', 'desc')->get();

        return view('ecommercant.dashboard', compact('totalColis', 'livres', 'enCours', 'retournes', 'recentAvis'));
    })->name('dashboard');

    Route::get('/finances', [WalletController::class, 'getWalletDetails'])->name('finances');
    Route::post('/finances/retrait', [RetraitController::class, 'demanderRetrait'])->name('retrait.store');

    Route::get('/colis', function () {
        $colis = \App\Models\Colis::where('ecommercant_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('ecommercant.colis.index', compact('colis'));
    })->name('colis.index');
    
    Route::post('/colis', [ColisController::class, 'store'])->name('colis.store');
    Route::get('/colis/creer', function () {
        return view('ecommercant.colis.create');
    })->name('colis.create');

    Route::get('/destinataires', function () {
        $destinataires = auth()->user()->destinataires()->latest()->get();
        return view('ecommercant.destinataires.index', compact('destinataires'));
    })->name('destinataires.index');

    Route::post('/destinataires/store', function (Illuminate\Http\Request $request) {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'adresse' => 'required|string',
        ]);

        \App\Models\Destinataire::create([
            'utilisateur_id' => auth()->id(),
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'ville' => $request->ville,
            'adresse' => $request->adresse,
        ]);

        return redirect()->back()->with('success', 'Destinataire enregistré avec succès !');
    })->name('destinataires.store');
});

/*
|--------------------------------------------------------------------------
| Espace Livreur (Protégé par Auth et le rôle livreur)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:livreur'])->prefix('livreur')->name('livreur.')->group(function () {
    Route::put('/colis/{id}/statut', [ColisController::class, 'updateStatut'])->name('colis.statut');
    
    Route::get('/dashboard', function () {
        $colisCount = Affectation::where('livreur_id', auth()->id())
            ->whereIn('statut', ['en_attente', 'en_cours'])
            ->count();
            
        $recentAvis = \App\Models\Avis::whereHas('colis', function($query) {
            $query->where('livreur_id', auth()->id());
        })->with('colis')->orderBy('created_at', 'desc')->get();
        
        return view('livreur.dashboard', compact('colisCount', 'recentAvis')); 
    })->name('dashboard');

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
| Route Temporaire de Génération
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
    
    return "Compte de test E-commerçant créé avec succès !";
}); 

/*
|--------------------------------------------------------------------------
| ⚙️ Routes Unifiées pour la Gestion du Profil
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/mon-profil', [UtilisateurController::class, 'editProfil'])->name('profil.edit');
    Route::post('/mon-profil/update', [UtilisateurController::class, 'updateProfil'])->name('profil.update');
});