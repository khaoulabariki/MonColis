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

    public function indexAdmin()
    {
        $adminsList = Utilisateur::where('role', 'admin')->get();
        return view('admin.administrateurs.index', compact('adminsList'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|min:6',
        ]);

        $adminUser = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'statut' => true
        ]);

        // 📝 Log précis pour la création d'un Admin
        $this->logAction('CREATION_ADMIN', "L'administrateur a créé un nouveau compte administrateur : {$adminUser->nom} {$adminUser->prenom}.", 'ADMIN');

        return redirect()->route('admin.administrateurs.index')->with('success', 'Administrateur ajouté avec succès !');
    }

    public function destroyAdmin($id)
    {
        // On récupère les infos avant la suppression pour avoir un Log d'audit propre
        $user = Utilisateur::find($id);
        $nomComplet = $user ? "{$user->nom} {$user->prenom}" : "ID: {$id}";

        Utilisateur::where('id', $id)->delete();

        // 📝 Log précis pour la suppression d'un Admin
        $this->logAction('SUPPRESSION_ADMIN', "L'administrateur a supprimé le compte de l'administrateur : {$nomComplet}.", 'ADMIN');

        return redirect()->route('admin.administrateurs.index')->with('success', 'Administrateur supprimé avec succès !');
    }

    /**
     * =========================================================================
     * 🚚 SECTION : GESTION DES LIVREURS
     * =========================================================================
     */

    public function indexLivreur()
    {
        $livreursList = Utilisateur::where('role', 'livreur')->get();
        return view('admin.livreurs.index', compact('livreursList'));
    }

    public function storeLivreur(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|min:6',
            'telephone' => 'nullable|string',
        ]);

        $livreurUser = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'livreur',
            'telephone' => $request->telephone ?? null,
            'statut' => true
        ]);

        // 📝 Log précis pour la création d'un Livreur
        $this->logAction('CREATION_LIVREUR', "L'administrateur a créé un nouveau compte livreur : {$livreurUser->nom} {$livreurUser->prenom}.", 'LIVREUR');

        return redirect()->route('admin.livreurs.index')->with('success', 'Livreur ajouté avec succès !');
    }

    public function destroyLivreur($id)
    {
        // On récupère les infos avant la suppression pour le Log
        $user = Utilisateur::find($id);
        $nomComplet = $user ? "{$user->nom} {$user->prenom}" : "ID: {$id}";

        Utilisateur::where('id', $id)->delete();

        // 📝 Log précis pour la suppression d'un Livreur (Fini le mot générique 'UTILISATEUR')
        $this->logAction('SUPPRESSION_LIVREUR', "L'administrateur a supprimé le compte du livreur : {$nomComplet}.", 'LIVREUR');

        return redirect()->route('admin.livreurs.index')->with('success', 'Livreur supprimé avec succès !');
    }

    /**
     * =========================================================================
     * 🛍️ SECTION : GESTION DES E-COMMERÇANTS
     * =========================================================================
     */

    public function indexEcom()
    {
        $ecommercantsList = Utilisateur::where('role', 'ecommercant')->get();
        return view('admin.ecommercants.index', compact('ecommercantsList'));
    }

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

        Wallet::create([
            'ecommercant_id' => $utilisateur->id,
            'solde' => 0.00
        ]);

        // 📝 Log précis pour la création d'un E-commerçant
        $this->logAction('CREATION_ECOMMERCANT', "L'administrateur a créé un nouveau compte e-commerçant : {$utilisateur->nom} {$utilisateur->prenom} avec un portefeuille virtuel.", 'ECOMMERCANT');

        return redirect()->route('admin.ecommercants.index')->with('success', 'E-commerçant ajouté avec succès !');
    }

    public function destroyEcom($id)
    {
        // On récupère les infos avant la suppression pour le Log
        $user = Utilisateur::find($id);
        $nomComplet = $user ? "{$user->nom} {$user->prenom}" : "ID: {$id}";

        Utilisateur::where('id', $id)->delete();

        // 📝 Log précis pour la suppression d'un E-commerçant
        $this->logAction('SUPPRESSION_ECOMMERCANT', "L'administrateur a supprimé le compte de l'e-commerçant : {$nomComplet}.", 'ECOMMERCANT');

        return redirect()->route('admin.ecommercants.index')->with('success', 'E-commerçant supprimé avec succès !');
    }
}