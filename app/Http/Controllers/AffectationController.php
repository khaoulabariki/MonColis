<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colis;
use App\Models\Utilisateur;
use App\Models\Affectation;

class AffectationController extends Controller
{
    /**
     * Afficher la page de gestion des affectations.
     */
    public function index()
    {
        // 1. Récupérer tous les colis enregistrés ou en cours
        $colis = Colis::whereIn('statut', ['enregistre', 'en_cours'])->orderBy('created_at', 'desc')->get();

        // 2. CORRECTION : On nomme la variable $livreurs pour correspondre exactement au compact et au Blade
        $livreurs = Utilisateur::where('role', 'livreur')->where('statut', true)->get();

        // 3. Récupérer l'historique des affectations
        $affectationsList = Affectation::with(['colis', 'livreur'])->orderBy('created_at', 'desc')->get();

        // Retourner la vue avec les variables exactes attendues par le fichier index.blade.php
        return view('admin.affectations.index', compact('colis', 'livreurs', 'affectationsList'));
    }

    /**
     * Traiter l'affectation d'un colis à un livreur.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'livreur_id' => 'required|exists:utilisateurs,id',
        ]);

        $colisActuel = Colis::findOrFail($id);
        
        $colisActuel->update([
            'livreur_id' => $request->livreur_id,
            'statut'     => 'en_cours'
        ]);

        Affectation::create([
            'colis_id' => $colisActuel->id,
            'livreur_id' => $request->livreur_id,
            'date_affectation' => now(),
            'statut' => 'en_cours'
        ]);

        return redirect()->back()->with('success', 'Le colis a été affecté au livreur avec succès !');
    }
}