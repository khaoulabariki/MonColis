<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction; 
use App\Models\Retrait;     

class TransactionController extends Controller
{
    /**
     * Afficher la page des finances de l'administration.
     */
    public function index()
    {
        // 1. Récupérer l'historique des transactions financières
        $transactionsList = Transaction::orderBy('created_at', 'desc')->get();

        // 2. Récupérer les demandes de retrait en attente de validation (si applicable)
        $retraitsAttente = Retrait::where('statut', 'en_attente')->orderBy('created_at', 'desc')->get();

        // 3. Retourner la vue Blade au lieu de renvoyer du JSON
        return view('admin.finances.index', compact('transactionsList', 'retraitsAttente'));
    }
}