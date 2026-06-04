<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Afficher le solde, l'historique des transactions et les retraits de l'e-commerçant connecté.
     */
    public function getWalletDetails()
    {
        // 1. Récupérer le portefeuille de l'utilisateur connecté avec ses transactions
        $wallet = Wallet::where('ecommercant_id', auth()->id())
            ->with('transactions')
            ->first();

        // 2. Récupérer les demandes de retraits de l'e-commerçant connecté
        // (On suppose que vous avez un modèle appelé Retrait ou que vous passez une collection vide si pas encore créé)
        $retraits = []; 
        if (class_exists('\App\Models\Retrait')) {
            $retraits = \App\Models\Retrait::where('ecommercant_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($wallet) {
            // Si les retraits sont liés aux transactions de type 'retrait'
            $retraits = $wallet->transactions->where('type', 'retrait');
        }

        // Si le portefeuille n'existe pas encore dans la base de données pour cet utilisateur, on crée un objet vide pour éviter les bugs
        if (!$wallet) {
            $wallet = new Wallet(['solde' => 0]);
        }

        // 3. Passer toutes les variables attendues par la vue Blade (wallet et retraits)
        return view('ecommercant.finances', compact('wallet', 'retraits'));
    }
}