<?php

namespace App\Http\Controllers;

use App\Models\Colis;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColisController extends Controller
{
    // Afficher tous les colis (Pour l'admin)
    public function index()
    {
        return response()->json(Colis::with(['ecommercant', 'livreur'])->get());
    }

    // Créer un nouveau colis (Par l'E-commerçant)
    public function store(Request $request)
    {
        $request->validate([
            'nom_destinataire' => 'required|string',
            'prenom_destinataire' => 'required|string',
            'telephone_destinataire' => 'required|string',
            'adresse_destinataire' => 'required|string',
            'poids' => 'required|numeric',
            'prix' => 'required|numeric',
            'ecommercant_id' => 'required|exists:utilisateurs,id',
        ]);

        // Génération automatique du code de suivi et token unique
        $codeSuivi = 'NWS-' . strtoupper(Str::random(8));
        $tokenSuivi = Str::uuid()->toString();

        $colis = Colis::create(array_merge(
            $request->all(),
            ['code_suivi' => $codeSuivi, 'token_suivi' => $tokenSuivi, 'statut' => 'enregistre']
        ));

        return response()->json(['message' => 'Colis enregistré avec succès', 'colis' => $colis], 201);
    }

    // Assigner un livreur au colis et créer une affectation
    public function assignLivreur(Request $request, $id)
    {
        $request->validate([
            'livreur_id' => 'required|exists:utilisateurs,id'
        ]);

        $colis = Colis::findOrFail($id);
        $colis->update([
            'livreur_id' => $request->livreur_id,
            'statut' => 'en_cours'
        ]);

        // Enregistrer l'action dans l'historique des affectations
        $colis->affectations()->create([
            'livreur_id' => $request->livreur_id,
            'date_affectation' => now(),
            'statut' => 'en_cours'
        ]);

        return response()->json(['message' => 'Livreur assigné et colis mis en cours', 'colis' => $colis]);
    }
}