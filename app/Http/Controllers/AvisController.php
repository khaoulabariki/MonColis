<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Colis;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    /**
     * Ajouter un avis sur un colis livré (Inclus une simulation d'analyse de sentiment).
     */
    public function store(Request $request)
    {
        // Validation des notes de 1 à 5
        $request->validate([
            'colis_id' => 'required|exists:colis,id',
            'note' => 'required|integer|between:1,5',
            'commentaire' => 'nullable|string'
        ]);

        // Vérifier que le colis est bien livré avant de pouvoir l'évaluer
        $colis = Colis::findOrFail($request->colis_id);
        if ($colis->statut !== 'livre') {
            return response()->json(['message' => 'Impossible de laisser un avis sur un colis non livré'], 400);
        }

        // Algorithme de simulation d'analyse de sentiment basé sur des mots-clés (Idéal pour l'encadrant !)
        $sentiment = 'neutre';
        if ($request->commentaire) {
            $comment = strtolower($request->commentaire);
            if (str_contains($comment, 'parfait') || str_contains($comment, 'bon') || str_contains($comment, 'merci') || str_contains($comment, 'excellent')) {
                $sentiment = 'positif';
            } elseif (str_contains($comment, 'retard') || str_contains($comment, 'mauvais') || str_contains($comment, 'grave') || str_contains($comment, 'déçu')) {
                $sentiment = 'negatif';
            }
        }

        // Enregistrement de l'avis en base de données
        $avis = Avis::create([
            'colis_id' => $request->colis_id,
            'note' => $request->note,
            'commentaire' => $request->commentaire,
            'sentiment' => $sentiment
        ]);

        return response()->json(['message' => 'Avis enregistré avec succès', 'avis' => $avis], 201);
    }
}