<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour la création de la table 'notifications'.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // Équivalent à id_notification (Clé primaire auto-incrémentée)
            
            // Relation avec l'utilisateur qui reçoit l'notification (Clé étrangère)
            $table->foreignId('utilisateur_id')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');
            
            $table->string('message'); // Contenu textuel de la notification
            
            $table->boolean('lu')->default(false); // État de la notification : false = non lue, true = lue
            
            $table->timestamps(); // Gère 'created_at' (équivalent à date_creation) et 'updated_at'
        });
    }

    /**
     * Annuler les migrations (Supprimer la table).
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};