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
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed l'application avec uniquement les 3 comptes principaux demandés.
     */
    public function run(): void
    {
        // 1. Désactiver les contraintes pour vider proprement la base de données
        Schema::disableForeignKeyConstraints();
        AuditLog::truncate();
        Avis::truncate();
        Affectation::truncate();
        Colis::truncate();
        Wallet::truncate();
        Utilisateur::truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Mot de passe global par défaut : password
        $passwordGlobal = Hash::make('password');

        // =========================================================================
        // 👥 1. CRÉATION DES 3 UTILISATEURS PRINCIPAUX (MODIFIÉS)
        // =========================================================================
        
        // 1️⃣ L'ADMINISTRATEUR (Khaoula Bariki)
        $admin = Utilisateur::create([
            'nom' => 'Bariki',
            'prenom' => 'Khaoula',
            'email' => 'admin@shipily.ma',
            'password' => $passwordGlobal,
            'role' => 'admin',
            'telephone' => '0661234567',
            'statut' => true,
        ]);

        // 2️⃣ L'E-COMMERÇANT (Ikram Belkhaoua)
        // الملاحظة: الـ role خليتو 'ecommercant' على حساب الـ Seeder القديم ديالك
        $ecom = Utilisateur::create([
            'nom' => 'Belkhaoua',
            'prenom' => 'Ikram',
            'email' => 'ecom@shipily.ma',
            'password' => $passwordGlobal,
            'role' => 'ecommercant', 
            'telephone' => '0667890123',
            'statut' => true,
        ]);

        // 3️⃣ LE LIVREUR (Amine El Alami)
        $livreur = Utilisateur::create([
            'nom' => 'El Alami',
            'prenom' => 'Amine',
            'email' => 'livreur@shipily.ma',
            'password' => $passwordGlobal,
            'role' => 'livreur',
            'telephone' => '0655443322',
            'statut' => true,
        ]);

        // =========================================================================
        // 📝 2. JOURNAL D'AUDIT (Pour garder une trace propre)
        // =========================================================================
        AuditLog::create([
            'utilisateur_id' => $admin->id,
            'action' => 'INITIALIZATION',
            'entite' => 'system',
            'donnees_avant' => null,
            'donnees_apres' => ['status' => 'Shipily cleaned. 0 colis, 0 comments, only 3 core accounts created.'],
        ]);
    }
}