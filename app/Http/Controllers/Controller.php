<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Enregistrer une action avec l'entité requise dans le journal d'audit.
     */
    protected function logAction($action, $description, $entite = 'SYSTEME')
    {
        // Récupérer l'ID de l'utilisateur connecté depuis la session ou via Auth Guard
        $userId = session('utilisateur_id') ?? (Auth::guard('web')->check() ? Auth::guard('web')->id() : null);

        // Insertion avec prise en compte du champ obligatoire 'entite'
        if ($userId) {
            AuditLog::create([
                'utilisateur_id' => $userId,
                'action'         => $action,
                'donnees_apres'  => ['description' => $description],
                'entite'         => $entite, 
                'created_at'     => now()
            ]);
        }
    }
}