<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Afficher le solde et l'historique des transactions d'un e-commerçant.
     */
    public function getWalletDetails($ecommercant_id)
    {
        // Récupérer le portefeuille avec tout son historique de transactions
        $wallet = Wallet::where('ecommercant_id', $ecommercant_id)
                        ->with('transactions')
                        ->firstOrFail();

        return response()->json([
            'solde' => $wallet->solde,
            'transactions' => $wallet->transactions
        ]);
    }
}