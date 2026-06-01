<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 1. Affichage de la liste complète sans bug de pagination
    public function index()
    {
        $users = Utilisateur::get();
        return view('admin.users.index', compact('users'));
    }

    // 2. Affichage du formulaire de création
    public function create()
    {
        return view('admin.users.create');
    }

    // 3. Enregistrement sécurisé m-syncronisé m3a l-formulaire
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'role' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'role' => $request->role,
            'statut' => 1, // Actif par défaut
            'mot_de_passe' => bcrypt($request->password), 
        ]);

        return redirect()->route('admin.users.index')->with('success', 'L\'utilisateur a été créé avec succès.');
    }

    // 4. Basculer le statut Actif / Inactif
    public function toggleStatus($id)
    {
        $user = Utilisateur::findOrFail($id);
        $user->statut = (int)$user->statut === 1 ? 0 : 1;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Le statut de l\'utilisateur a été modifié.');
    }

    // Pour afficher uniquement les Livreurs f l-admin
    public function livreursInline()
    {
        $users = \App\Models\Utilisateur::where('role', 'livreur')->get();
        return view('admin.users.index', compact('users')); // N-utilisiw nfs la vue nqiya
    }

    // Pour afficher uniquement les E-commerçants f l-admin
    public function ecomercantsInline()
    {
        $users = \App\Models\Utilisateur::whereNotIn('role', ['admin','livreur'])->get();
        return view('admin.users.index', compact('users')); 
    }
}