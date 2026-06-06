<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion (Vue Login Blade).
     */
    public function showLogin()
    {
        // Retourne la vue auth.login.blade.php qui est bien à sa place
        return view('auth.login');
    }

    /**
     * Afficher le formulaire d'inscription autonome (Vue Register Blade).
     */
    public function showRegister()
    {
        // Retourne la vue auth.register.blade.php pour permettre l'inscription
        return view('auth.register');
    }

    /**
     * Connexion de l'utilisateur (Gestion Session Web et API).
     */
    public function login(Request $request)
    {
        // Validation des champs du formulaire
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Recherche de l'utilisateur par son email
        $utilisateur = Utilisateur::where('email', $request->email)->first();

        // Vérification des identifiants (Email existant et mot de passe correspondant)
        if (!$utilisateur || !Hash::check($request->password, $utilisateur->password)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Identifiants incorrects'], 401);
            }
            return redirect()->back()->withErrors(['email' => 'Identifiants incorrects'])->withInput();
        }

        // Vérifier si le compte est actif (statut = true)
        if (!$utilisateur->statut) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Votre compte est inactif'], 403);
            }
            return redirect()->back()->withErrors(['email' => 'Votre compte est actuellement inactif.'])->withInput();
        }

        // 🎯 CORRECTION SÉCURITÉ & SESSION : 
        // Force l'authentification sur le guard 'web' et active le 'Remember Me' (true)
        Auth::guard('web')->login($utilisateur, true);

        // Sauvegarde explicite de l'ID en session pour consolider la persistance
        $request->session()->put('utilisateur_id', $utilisateur->id);
        $request->session()->regenerate(); // Régénère la session pour éviter les attaques de fixation

        // Si la requête attend du JSON (Appels API / Applications mobiles)
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Connexion réussie',
                'utilisateur' => $utilisateur,
                'role' => $utilisateur->role
            ]);
        }

        // Redirection automatique selon le rôle de l'utilisateur pour la soutenance
        if ($utilisateur->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($utilisateur->role === 'ecommercant') {
            return redirect()->route('ecommercant.dashboard');
        } elseif ($utilisateur->role === 'livreur') {
            return redirect()->route('livreur.dashboard');
        }

        // Redirection par défaut si aucun rôle ne correspond
        return redirect('/tracking');
    }

    /**
     * Inscription d'un nouveau E-commerçant ou Livreur.
     */
    public function register(Request $request)
    {
        // Validation des données d'inscription
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'password' => 'required|string|min:6',
            'role' => 'required|in:ecommercant,livreur',
            'telephone' => 'nullable|string'
        ]);

        // Création de l'utilisateur avec hachage du mot de passe
        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telephone' => $request->telephone,
            'statut' => true // Le compte est actif par défaut
        ]);

        // Si c'est un e-commerçant, on lui crée automatiquement son Wallet (Portefeuille)
        if ($utilisateur->role === 'ecommercant') {
            Wallet::create([
                'ecommercant_id' => $utilisateur->id,
                'solde' => 0.00
            ]);
        }

        // Si la requête provient d'une API mobile (attend du JSON)
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Compte créé avec succès', 'utilisateur' => $utilisateur], 201);
        }

        // 🎯 POUR LE WEB : Connecter automatiquement le nouvel utilisateur après inscription
        Auth::guard('web')->login($utilisateur, true);
        $request->session()->put('utilisateur_id', $utilisateur->id);
        $request->session()->regenerate();

        // Redirection instantanée vers le bon Dashboard selon le rôle choisi
        if ($utilisateur->role === 'ecommercant') {
            return redirect()->route('ecommercant.dashboard')->with('success', 'Votre compte Pro E-commerçant a été créé avec succès !');
        } elseif ($utilisateur->role === 'livreur') {
            return redirect()->route('livreur.dashboard')->with('success', 'Votre compte de Livreur a été créé avec succès !');
        }

        return redirect('/tracking');
    }

    /**
     * Déconnecter l'utilisateur et fermer la session.
     */
    public function logout(Request $request)
    {
        // Déconnexion globale de la session sur le guard web
        Auth::guard('web')->logout();

        // Invalidation complète et nettoyage des jetons de session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Déconnexion réussie.');
    }
}