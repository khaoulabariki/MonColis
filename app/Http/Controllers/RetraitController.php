<?php

namespace App\Http\Controllers;

use App\Models\Retrait;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetraitController extends Controller
{
    /**
     * 🟢 1. Créer une demande de retrait (E-commerçant)
     * Déduit le solde immédiatement et répond en JSON si la requête est en AJAX.
     */
    public function demanderRetrait(Request $request)
    {
        // 🎯 Validation avec messages d'erreur personnalisés en Français
        $request->validate([
            'ecommercant_id' => 'required|exists:utilisateurs,id',
            'montant' => 'required|numeric|min:100'
        ], [
            'montant.min' => 'Le montant doit être au moins 100.',
            'montant.numeric' => 'Le montant doit être un nombre valide.',
            'montant.required' => 'Le montant est obligatoire.'
        ]);

        $wallet = Wallet::where('ecommercant_id', $request->ecommercant_id)->firstOrFail();
        
        if ((float)$wallet->solde < (float)$request->montant) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Solde insuffisant pour effectuer ce retrait !'], 422);
            }
            return redirect()->back()->with('error', 'Solde insuffisant pour effectuer ce retrait !');
        }

        // 📉 Déduction immédiate du solde pour cohérence visuelle chez le marchand
        DB::table('wallets')
            ->where('ecommercant_id', $request->ecommercant_id)
            ->decrement('solde', $request->montant);

        $retrait = Retrait::create([
            'ecommercant_id' => $request->ecommercant_id,
            'montant' => $request->montant,
            'statut' => 'en_attente'
        ]);

        // ✨ Réponse JSON si la requête provient d'un script AJAX (évite l'erreur rouge du Modal)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Votre demande a été enregistrée et le solde mis à jour !',
                'retrait' => $retrait
            ], 200);
        }

        return redirect()->back()->with('success', 'Votre demande a été enregistrée et le solde mis à jour !');
    }

    /**
     * 🔵 2. Traiter la demande de retrait (Admin : Valider ou Rejeter)
     * Valide définitivement l'action ou ré-incrémente le solde en cas de rejet.
     */
    public function traiterRetrait(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:valide,rejete'
        ]);

        $retrait = Retrait::findOrFail($id);
        
        if ($retrait->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
        }

        // 🔄 Si l'admin rejette la demande, on restitue l'argent sur le solde du marchand
        if ($request->statut === 'rejete') {
            DB::table('wallets')
                ->where('ecommercant_id', $retrait->ecommercant_id)
                ->increment('solde', $retrait->montant);
        } else {
            // Si validé, on enregistre formellement la transaction financière finale
            $wallet = Wallet::where('ecommercant_id', $retrait->ecommercant_id)->first();
            if ($wallet) {
                Transaction::create([
                    'wallet_id'   => $wallet->id,
                    'montant'     => $retrait->montant,
                    'type'        => 'debit',
                    'description' => "Retrait validé pour le marchand #{$retrait->ecommercant_id}"
                ]);
            }
        }

        $retrait->update(['statut' => $request->statut]);

        return redirect()->back()->with('success', 'Le statut a été mis à jour avec succès !');
    }
}