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

        // Vérifier si le solde du portefeuille est suffisant pour le retrait (Créer le portefeuille s'il n'existe pas)
        $wallet = Wallet::firstOrCreate(
            ['ecommercant_id' => $request->ecommercant_id],
            ['solde' => 0]
        );
        
        $retraitsEnAttente = Retrait::where('ecommercant_id', $request->ecommercant_id)
            ->where('statut', 'en_attente')
            ->sum('montant');
            
        $soldeDisponible = $wallet->solde - $retraitsEnAttente;

        if ($soldeDisponible < $request->montant) {
            return response()->json(['message' => __('Solde insuffisant pour effectuer ce retrait')], 400);
        }

        // Création de la demande avec le statut 'en_attente'
        $retrait = Retrait::create([
            'ecommercant_id' => $request->ecommercant_id,
            'montant' => $request->montant,
            'statut' => 'en_attente'
        ]);

        return response()->json(['message' => __('Demande de retrait enregistrée avec succès'), 'retrait' => $retrait], 201);
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
            // Si la requête vient du formulaire HTML, on redirige avec un message d'erreur
            if (!$request->expectsJson()) {
                return redirect()->route('admin.finances.index')->with('error', __('Cette demande a déjà été traitée.'));
            }
            return response()->json(['message' => __('Cette demande a déjà été traitée')], 400);
        }

        // Si l'admin valide le retrait, on vérifie si le solde est suffisant
        if ($request->statut === 'valide') {
            $wallet = Wallet::firstOrCreate(
                ['ecommercant_id' => $retrait->ecommercant_id],
                ['solde' => 0]
            );
            
            // Vérifier une dernière fois si le solde est suffisant avant de déduire
            if ($wallet->solde < $retrait->montant) {
                if (!$request->expectsJson()) {
                    return redirect()->route('admin.finances.index')->with('error', __('Solde insuffisant pour ce marchand.'));
                }
                return response()->json(['message' => __('Solde insuffisant')], 400);
            }
        }

        // Mise à jour du statut de la demande (Le hook dans le modèle Retrait s'occupera de la déduction)
        $retrait->update(['statut' => $request->statut]);

        // Redirection propre vers la page des finances avec un message de succès
        if (!$request->expectsJson()) {
            return redirect()->route('admin.finances.index')->with('success', __('La demande de retrait a été mise à jour avec succès !'));
        }

        return response()->json(['message' => __('Demande de retrait mise à jour :') . ' ' . $request->statut, 'retrait' => $retrait]);
    }
}