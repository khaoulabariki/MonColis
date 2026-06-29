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
    public function cloturerLivreur($livreur_id)
{
    // Mettre à jour tous les colis livrés par ce livreur pour indiquer qu'ils ont été encaissés par l'administration
    \App\Models\Colis::where('livreur_id', $livreur_id)
                     ->where('statut', 'livre')
                     ->where('encaissement_admin', false)
                     ->update(['encaissement_admin' => true]);

    return redirect()->back()->with('success', 'La caisse de ce livreur a été clôturée avec succès !');
}
}