<?php

namespace App\Http\Controllers;

use App\Models\Colis;
use App\Models\Utilisateur;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColisController extends Controller
{
    // Liste des colis (Admin)
    public function index()
    {
        $colis = Colis::with('ecomercant')->latest()->paginate(15);
        return view('admin.colis.index', compact('colis'));
    }

    // Liste des colis (E-commerçant)
    public function mesColis()
    {
        $colis = Colis::where('ecomercant_id', Auth::id())
                      ->latest()
                      ->paginate(15);
        return view('ecomercant.colis.index', compact('colis'));
    }

    // Formulaire créer colis
    public function create()
    {
        return view('ecomercant.colis.create');
    }

    // Enregistrer colis
    public function store(Request $request)
    {
        $request->validate([
            'nom_destinataire'      => 'required|string',
            'prenom_destinataire'   => 'required|string',
            'telephone_destinataire'=> 'required|string',
            'adresse_destinataire'  => 'required|string',
            'poids'                 => 'required|numeric|min:0',
            'prix'                  => 'required|numeric|min:0',
        ]);

        $colis = Colis::create([
            'nom_destinataire'       => $request->nom_destinataire,
            'prenom_destinataire'    => $request->prenom_destinataire,
            'telephone_destinataire' => $request->telephone_destinataire,
            'adresse_destinataire'   => $request->adresse_destinataire,
            'poids'                  => $request->poids,
            'prix'                   => $request->prix,
            'ecomercant_id'          => Auth::id(),
        ]);

        // Audit log
        AuditLog::create([
            'utilisateur_id' => Auth::id(),
            'action'         => 'création',
            'entite'         => 'Colis',
            'donnees_avant'  => null,
            'donnees_apres'  => $colis->toArray(),
        ]);

        return redirect()->route('ecomercant.colis.index')
                         ->with('success', 'Colis enregistré avec succès !');
    }

    // Détail colis
    public function show(Colis $colis)
    {
        return view('ecomercant.colis.show', compact('colis'));
    }

    // Page tracking public
    public function tracking($token)
    {
        $colis = Colis::where('token_suivi', $token)->firstOrFail();
        return view('public.tracking', compact('colis'));
    }

    // Changer statut (Livreur)
    public function changerStatut(Request $request, Colis $colis)
    {
        $request->validate([
            'statut' => 'required|in:en_cours,livre,retourne',
        ]);

        $avant = $colis->toArray();
        $colis->update(['statut' => $request->statut]);

        AuditLog::create([
            'utilisateur_id' => Auth::id(),
            'action'         => 'changement statut',
            'entite'         => 'Colis',
            'donnees_avant'  => $avant,
            'donnees_apres'  => $colis->fresh()->toArray(),
        ]);

        return back()->with('success', 'Statut mis à jour !');
    }
}
