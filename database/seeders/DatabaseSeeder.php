<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use App\Models\Colis;
use App\Models\Wallet;
use App\Models\Affectation;
use App\Models\Avis;
use App\Models\AuditLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed l'application avec des données prêtes pour la soutenance.
     */
    public function run(): void
    {
        // 1. CRÉATION DES UTILISATEURS DE TEST (Pour se connecter demain)
        
        $admin = Utilisateur::create([
            'nom' => 'Alami',
            'prenom' => 'Ahmed',
            'email' => 'admin@nwsallik.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'telephone' => '0661234567',
            'statut' => true,
        ]);

        $ecommercant = Utilisateur::create([
            'nom' => 'Benani',
            'prenom' => 'Youssef',
            'email' => 'ecom@nwsallik.com',
            'password' => Hash::make('password123'),
            'role' => 'ecommercant',
            'telephone' => '0667890123',
            'statut' => true,
        ]);

        $livreur = Utilisateur::create([
            'nom' => 'Tazi',
            'prenom' => 'Karim',
            'email' => 'livreur@nwsallik.com',
            'password' => Hash::make('password123'),
            'role' => 'livreur',
            'telephone' => '0655443322',
            'statut' => true,
        ]);

        // 2. CRÉATION DU PORTEFEUILLE (WALLET) POUR L'E-COMMERÇANT
        $wallet = Wallet::create([
            'ecommercant_id' => $ecommercant->id,
            'solde' => 1550.00, // Solde fictif initial pour alimenter l'interface finances
        ]);

        // 3. CRÉATION DE QUELQUES COLIS AVEC DIFFÉRENTS STATUTS (Pour le Dashboard de demain)
        
        // Colis 1 : Enregistré (Non encore affecté)
        $colis1 = Colis::create([
            'code_suivi' => 'NWS-REG12345',
            'nom_destinataire' => 'Hakimi',
            'prenom_destinataire' => 'Achraf',
            'telephone_destinataire' => '0611223344',
            'adresse_destinataire' => 'Casablanca, Maarif',
            'poids' => 1.5,
            'prix' => 350.00,
            'statut' => 'enregistre',
            'token_suivi' => (string) Str::uuid(),
            'ecommercant_id' => $ecommercant->id,
        ]);

        // Colis 2 : En cours de livraison (Affecté au livreur)
        $colis2 = Colis::create([
            'code_suivi' => 'NWS-ENCOURS88',
            'nom_destinataire' => 'Bounou',
            'prenom_destinataire' => 'Yassine',
            'telephone_destinataire' => '0677889900',
            'adresse_destinataire' => 'Rabat, Agdal',
            'poids' => 0.8,
            'prix' => 200.00,
            'statut' => 'En cours', // Match avec les statuts du web.php
            'token_suivi' => (string) Str::uuid(),
            'ecommercant_id' => $ecommercant->id,
            'livreur_id' => $livreur->id,
        ]);

        // Historique d'affectation pour le colis en cours
        Affectation::create([
            'colis_id' => $colis2->id,
            'livreur_id' => $livreur->id,
            'date_affectation' => now()->subHours(2),
            'statut' => 'en_cours',
        ]);

        // Colis 3 : Déjà Livré (Avec un Avis Client positif pour déclencher l'IA du Dashboard)
        $colis3 = Colis::create([
            'code_suivi' => 'NWS-DELIV555',
            'nom_destinataire' => 'Amrabat',
            'prenom_destinataire' => 'Sofyan',
            'telephone_destinataire' => '0644332211',
            'adresse_destinataire' => 'Marrakech, Gueliz',
            'poids' => 2.0,
            'prix' => 600.00,
            'statut' => 'Livré', // Match avec les statuts du web.php
            'token_suivi' => (string) Str::uuid(),
            'ecommercant_id' => $ecommercant->id,
            'livreur_id' => $livreur->id,
        ]);

        Affectation::create([
            'colis_id' => $colis3->id,
            'livreur_id' => $livreur->id,
            'date_affectation' => now()->subDays(1),
            'statut' => 'livre',
        ]);

        // Ajouter un avis positif pour faire plaisir à l'analyseur IA du dashboard
        Avis::create([
            'colis_id' => $colis3->id,
            'note' => 5,
            'commentaire' => 'Service excellent, livraison très rapide merci beaucoup !',
            'sentiment' => 'positif',
        ]);

        // 4. CRÉATION D'UN LOG D'AUDIT INITIAL
        AuditLog::create([
            'utilisateur_id' => $admin->id,
            'action' => 'INITIALIZATION',
            'entite' => 'system',
            'donnees_avant' => null,
            'donnees_apres' => ['status' => 'System successfully populated for demonstration'],
        ]);
    }
}