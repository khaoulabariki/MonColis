<?php

namespace App\Http\Controllers;

use App\Models\Retrait;
use App\Models\Wallet;
use Illuminate\Http\Request;

class RetraitController extends Controller
{
    /**
     * Créer une demande de retrait de fonds (Par le E-commerçant).
     */
    public function demanderRetrait(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'ecommercant_id' => 'required|exists:utilisateurs,id',
            'montant' => 'required|numeric|min:10'
        ]);

        // Vérifier si le solde du portefeuille est suffisant pour le retrait
        $wallet = Wallet::where('ecommercant_id', $request->ecommercant_id)->firstOrFail();
        if ($wallet->solde < $request->montant) {
            return response()->json(['message' => 'Solde insuffisant pour effectuer ce retrait'], 400);
        }

        // Création de la demande avec le statut 'en_attente'
        $retrait = Retrait::create([
            'ecommercant_id' => $request->ecommercant_id,
            'montant' => $request->montant,
            'statut' => 'en_attente'
        ]);

        return response()->json(['message' => 'Demande de retrait enregistrée avec succès', 'retrait' => $retrait], 201);
    }

    /**
     * Traiter la demande de retrait (Par l'Admin : Valider ou Rejeter).
     */
    public function traiterRetrait(Request $request, $id)
    {
        // Validation du nouveau statut
        $request->validate([
            'statut' => 'required|in:valide,rejete'
        ]);

        $retrait = Retrait::findOrFail($id);
        
        // Empêcher de traiter une demande déjà validée ou rejetée
        if ($retrait->statut !== 'en_attente') {
            return response()->json(['message' => 'Cette demande a déjà été traitée'], 400);
        }

        // Si l'admin valide le retrait, on déduit l'argent du portefeuille
        if ($request->statut === 'valide') {
            $wallet = Wallet::where('ecommercant_id', $retrait->ecommercant_id)->firstOrFail();
            
            // Déduire le montant du solde de l'e-commerçant
            $wallet->decrement('solde', $retrait->montant);

            // Enregistrer une transaction financière de type 'debit' (Sortie d'argent)
            $wallet->transactions()->create([
                'type' => 'debit',
                'montant' => $retrait->montant,
                'description' => 'Retrait de fonds validé par l\'administration (ID de retrait : ' . $retrait->id . ')'
            ]);
        }

        // Mise à jour du statut de la demande
        $retrait->update(['statut' => $request->statut]);

        return response()->json(['message' => 'Demande de retrait mise à jour : ' . $request->statut, 'retrait' => $retrait]);
    }
}