<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour la création de la table 'avis'.
     */
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id(); // Équivalent à id_avis (Clé primaire auto-incrémentée)
            
            // Relation avec la table 'colis' évaluée (Clé étrangère)
            $table->foreignId('colis_id')
                  ->constrained('colis')
                  ->onDelete('cascade');
            
            // Note attribuée au colis (Ex: de 1 à 5). 
            // On utilise unsignedTinyInteger pour optimiser le stockage des petits nombres
            $table->unsignedTinyInteger('note'); 
            
            $table->text('commentaire')->nullable(); // Commentaire texte optionnel laissé par l'utilisateur
            
            // Analyse de sentiment optionnelle du commentaire (Positif, Neutre, Négatif)
            $table->enum('sentiment', ['positif', 'neutre', 'negatif'])->nullable();
            
            $table->timestamps(); // Gère automatiquement 'created_at' et 'updated_at'
        });
    }

    /**
     * Annuler les migrations (Supprimer la table).
     */
    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};