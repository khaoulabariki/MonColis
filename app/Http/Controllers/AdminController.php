<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Colis;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Afficher les statistiques générales pour le tableau de bord de l'administration.
     */
    public function getDashboardStats()
    {
        return response()->json([
            'total_utilisateurs' => Utilisateur::count(),
            'total_colis' => Colis::count(),
            'colis_livres' => Colis::where('statut', 'livre')->count(),
            'colis_en_cours' => Colis::where('statut', 'en_cours')->count(),
        ]);
    }

    /**
     * Afficher l'historique global du système (Audit Logs).
     */
    public function getAuditLogs()
    {
        // Récupérer les derniers logs d'audit avec les détails des utilisateurs associés
        return response()->json(AuditLog::with('utilisateur')->latest()->get());
    }
}