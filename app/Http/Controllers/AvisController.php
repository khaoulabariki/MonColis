<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Colis;
use Illuminate\Http\Request;
use App\Models\AuditLog;
class AvisController extends Controller
{
    /**
     * Enregistrer l'avis du destinataire et analyser le sentiment via l'IA (Idéal pour la soutenance).
     */
    public function store(Request $request, $token)
    {
        // 1. Validation du champ commentaire envoyé depuis l'interface publique
        $request->validate([
            'commentaire' => 'required|string|max:1000'
        ]);

        // 2. Recherche du colis via son Token de suivi unique ou son code de suivi
        $colis = Colis::where('token_suivi', $token)
                      ->orWhere('code_suivi', $token)
                      ->first();

        // Si le colis n'existe pas dans la base de données
        if (!$colis) {
            return redirect()->back()->with('error', 'Impossible d\'associer cet avis à un colis valide.');
        }

        // 3. Vérification sécuritaire : le colis doit être livré pour pouvoir laisser un avis
        if (strtolower($colis->statut) !== 'livre' && strtolower($colis->statut) !== 'livré') {
            return redirect()->back()->with('error', 'Impossible de laisser un avis sur un colis non livré.');
        }

        // 4. Algorithme intelligent d'analyse de sentiment basé sur des mots-clés (Simulation IA)
        $sentiment = 'neutre';
        if ($request->commentaire) {
            $comment = strtolower($request->commentaire);
            
            // Mots-clés positifs pour booster le taux de satisfaction globale
            if (str_contains($comment, 'parfait') || str_contains($comment, 'bon') || str_contains($comment, 'merci') || str_contains($comment, 'excellent') || str_contains($comment, 'top') || str_contains($comment, 'mzyan')) {
                $sentiment = 'positif';
            } 
            // Mots-clés négatifs pour détecter les anomalies de livraison
            elseif (str_contains($comment, 'retard') || str_contains($comment, 'mauvais') || str_contains($comment, 'grave') || str_contains($comment, 'déçu') || str_contains($comment, 'casse')) {
                $sentiment = 'negatif';
            }
        }

        // 5. Enregistrement de l'avis enrichi par l'IA dans la base de données
        Avis::create([
            'colis_id'    => $colis->id,
            'note'        => 5, // Note structurelle par défaut
            'commentaire' => $request->commentaire,
            'sentiment'   => $sentiment // Sauvegarde du résultat de l'analyse sentimentale
        ]);
        
        // Simulation du Log système : On attribue l'action au système global ou au colis concerné
        AuditLog::create([
         'utilisateur_id' => $colis->ecommercant_id, // Lié à l'éco-commerçant propriétaire du colis pour le suivi
         'action'         => 'ANALYSE_IA',
         'donnees_apres'  => ['description' => "L'IA a analysé un nouvel avis pour le colis {$colis->code_suivi}. Sentiment détecté : " . strtoupper($sentiment)],
         'entite'        => 'SYSTÈME',
         'created_at'     => now()
      ]);
        // 6. Redirection avec un message flash de succès pour le design de l'interface
        return redirect()->back()->with('success', 'Votre avis a été enregistré et analysé avec succès par notre IA !');
    }
}