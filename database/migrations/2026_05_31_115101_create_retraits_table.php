<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour la création de la table 'retraits'.
     */
    public function up(): void
    {
        Schema::create('retraits', function (Blueprint $table) {
            $table->id(); // Équivalent à id_retrait (Clé primaire auto-incrémentée)
            
            // Relation avec le e-commerçant qui demande le retrait (Clé étrangère)
            // Modification: 'ecommercant_id' avec deux 'm' pour la cohérence globale
            $table->foreignId('ecommercant_id')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');
                  
            $table->decimal('montant', 10, 2); // Montant demandé pour le retrait (Ex: 500.00 DH)
            
            // Statut de la demande de retrait
            $table->enum('statut', [
                'en_attente', 
                'valide', 
                'rejete'
            ])->default('en_attente');
            
            $table->timestamps(); // Gère 'created_at' (équivalent à date_demande) et 'updated_at'
        });
    }

    /**
     * Annuler les migrations (Supprimer la table).
     */
    public function down(): void
    {
        Schema::dropIfExists('retraits');
    }
};