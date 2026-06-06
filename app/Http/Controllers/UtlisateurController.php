<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Colis;
use App\Models\Utilisateur;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    /**
     * =========================================================================
     * 👥 SECTION : GESTION DES ADMINISTRATEURS
     * =========================================================================
     */

    /**
     * Afficher la liste de tous les administrateurs.
     */
    public function indexAdmin()
    {
        $adminsList = Utilisateur::where('role', 'admin')->get();
        return view('admin.administrateurs.index', compact('adminsList'));
    }

    /**
     * Enregistrer un nouvel administrateur dans la base de données.
     */
    public function storeAdmin(Request $request)
    {
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
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'statut' => true
        ]);

        return redirect()->route('admin.administrateurs.index')->with('success', 'Administrateur ajouté avec succès !');
    }

    /**
     * Supprimer un administrateur spécifique de la base de données.
     */
    public function destroyAdmin($id)
    {
        Utilisateur::where('id', $id)->delete();
        return redirect()->route('admin.administrateurs.index')->with('success', 'Administrateur supprimé avec succès !');
    }

    /**
     * =========================================================================
     * 🚚 SECTION : GESTION DES LIVREURS
     * =========================================================================
     */

    /**
     * Afficher la liste de tous les livreurs.
     */
    public function indexLivreur()
    {
        $livreursList = Utilisateur::where('role', 'livreur')->get();
        return view('admin.livreurs.index', compact('livreursList'));
    }

    /**
     * Enregistrer un nouveau livreur dans la base de données.
     */
    public function storeLivreur(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|min:6',
            'telephone' => 'nullable|string',
        ]);

        Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'livreur',
            'telephone' => $request->telephone ?? null,
            'statut' => true
        ]);

        return redirect()->route('admin.livreurs.index')->with('success', 'Livreur ajouté avec succès !');
    }

    /**
     * Supprimer un livreur spécifique de la base de données.
     */
    public function destroyLivreur($id)
    {
        Utilisateur::where('id', $id)->delete();
        return redirect()->route('admin.livreurs.index')->with('success', 'Livreur supprimé avec succès !');
    }

    /**
     * =========================================================================
     * 🛍️ SECTION : GESTION DES E-COMMERÇANTS
     * =========================================================================
     */

    /**
     * Afficher la liste de tous les e-commerçants.
     */
    public function indexEcom()
    {
        $ecommercantsList = Utilisateur::where('role', 'ecommercant')->get();
        return view('admin.ecommercants.index', compact('ecommercantsList'));
    }

    /**
     * Enregistrer un nouveau e-commerçant et lui créer automatiquement un portefeuille (Wallet).
     */
    public function storeEcom(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|min:6',
            'telephone' => 'nullable|string',
        ]);

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'ecommercant',
            'telephone' => $request->telephone ?? null,
            'statut' => true
        ]);

        // Création automatique du Wallet pour le e-commerçant créé par l'admin
        Wallet::create([
            'ecommercant_id' => $utilisateur->id,
            'solde' => 0.00
        ]);

        return redirect()->route('admin.ecommercants.index')->with('success', 'E-commerçant ajouté avec succès !');
    }

    /**
     * Supprimer un e-commerçant spécifique de la base de données.
     */
    public function destroyEcom($id)
    {
        Utilisateur::where('id', $id)->delete();
        return redirect()->route('admin.ecommercants.index')->with('success', 'E-commerçant supprimé avec succès !');
    }

    /**
     * =========================================================================
     * 📊 ANCIENNES METHODES API (Conservées pour la compatibilité)
     * =========================================================================
     */

    /**
     * Afficher les statistiques générales pour le tableau de bord de l'administration via API.
     */
    public function getDashboardStats()
    {
        return response()->json([
            'total_utilisateurs' => Utilisateur::count(),
            'total_colis' => Colis::count(),
            'colis_livres' => Colis::where('statut', 'livre')->count(),
            'colis_en_cours' => Colis::where('statut', 'en_cours')->count(),
        ]);
    }

    /**
     * Afficher l'historique global du système (Audit Logs) via API.
     */
    public function getAuditLogs()
    {
        return response()->json(AuditLog::with('utilisateur')->latest()->get());
    }
}