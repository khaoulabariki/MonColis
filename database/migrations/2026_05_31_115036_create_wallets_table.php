<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour la création de la table 'wallets'.
     */
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id(); // Équivalent à id_wallet (Clé primaire auto-incrémentée)
            
            // Relation avec le e-commerçant propriétaire du wallet (Clé étrangère)
            // Modification: 'ecommercant_id' avec deux 'm' pour rester cohérent
            $table->foreignId('ecommercant_id')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');
                  
            $table->decimal('solde', 10, 2)->default(0.00); // Solde actuel du portefeuille (Ex: 0.00 DH)
            
            $table->timestamps(); // Gère automatiquement 'created_at' et 'updated_at' (équivalent à date_update)
        });
    }

    /**
     * Annuler les migrations (Supprimer la table).
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};