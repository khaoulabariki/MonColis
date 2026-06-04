<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour la création de la table 'affectations'.
     */
    public function up(): void
    {
        Schema::create('affectations', function (Blueprint $table) {
            $table->id(); // Équivalent à id_affectation (Clé primaire auto-incrémentée)
            
            // Relation avec la table 'colis' (Clé étrangère)
            $table->foreignId('colis_id')
                  ->constrained('colis')
                  ->onDelete('cascade');
            
            // Relation avec la table 'utilisateurs' pour le livreur (Clé étrangère)
            $table->foreignId('livreur_id')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');
                  
            $table->date('date_affectation'); // Date de l'affectation du colis au livreur
            
            // Statut de l'affectation (Ex: en_attente, en_cours, valide)
            $table->string('statut')->default('en_attente'); 
            
            $table->timestamps(); // Colonnes 'created_at' et 'updated_at'
        });
    }

    /**
     * Annuler les migrations (Supprimer la table).
     */
    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};