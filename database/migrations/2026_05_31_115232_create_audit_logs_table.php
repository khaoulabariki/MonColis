<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour la création de la table 'audit_logs'.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id(); // Équivalent à id_log (Clé primaire auto-incrémentée)
            
            // Relation avec l'utilisateur qui a effectué l'action (Clé étrangère)
            $table->foreignId('utilisateur_id')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');
            
            $table->string('action'); // L'action effectuée (Ex: 'CREATE', 'UPDATE', 'DELETE')
            $table->string('entite'); // L'entité ou la table concernée (Ex: 'colis', 'retraits')
            
            // Stockage des données sous format JSON pour tracer les changements
            $table->json('donnees_avant')->nullable(); // État des données avant l'action
            $table->json('donnees_apres')->nullable(); // État des données après l'action
            
            $table->timestamps(); // Gère 'created_at' (équivalent à date_creation) et 'updated_at'
        });
    }

    /**
     * Annuler les migrations (Supprimer la table).
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};