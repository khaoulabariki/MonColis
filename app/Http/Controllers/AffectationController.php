<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Colis;
use App\Models\Utilisateur;
use App\Models\AuditLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffectationController extends Controller
{
    // Formulaire affectation
    public function create(Colis $colis)
    {
        $livreurs = Utilisateur::where('role', 'livreur')
                               ->where('statut', true)
                               ->get();
        return view('admin.colis.affecter', compact('colis', 'livreurs'));
    }

    // Enregistrer affectation
    public function store(Request $request, Colis $colis)
    {
        $request->validate([
            'livreur_id' => 'required|exists:utilisateurs,id',
        ]);

        // Créer l'affectation
        $affectation = Affectation::create([
            'colis_id'         => $colis->id,
            'livreur_id'       => $request->livreur_id,
            'date_affectation' => now(),
            'statut'           => 'en_attente',
        ]);

        // Changer statut colis → ramasse
        $avant = $colis->toArray();
        $colis->update(['statut' => 'ramasse']);

        // Notification au livreur
        Notification::create([
            'utilisateur_id' => $request->livreur_id,
            'message'        => "Nouveau colis affecté : {$colis->code_suivi}",
            'lu'             => false,
        ]);

        // Audit log
        AuditLog::create([
            'utilisateur_id' => Auth::id(),
            'action'         => 'affectation',
            'entite'         => 'Colis',
            'donnees_avant'  => $avant,
            'donnees_apres'  => $colis->fresh()->toArray(),
        ]);

        return redirect()->route('admin.colis.index')
                         ->with('success', "Colis {$colis->code_suivi} affecté avec succès !");
    }

    // Liste affectations livreur
    public function mesAffectations()
    {
        $affectations = Affectation::with('colis')
                                   ->where('livreur_id', Auth::id())
                                   ->latest()
                                   ->get();
        return view('livreur.colis.index', compact('affectations'));
    }
}