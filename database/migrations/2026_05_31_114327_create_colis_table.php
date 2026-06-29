<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour la création de la table 'colis'.
     */
    public function up(): void
    {
        // 🛠️ كنحبسو الحماية ديال المفاتيح الأجنبية مؤقتاً باش يتكرى الطابل بلا مشاكل الترتيب
        Schema::disableForeignKeyConstraints();

        Schema::create('colis', function (Blueprint $table) {
            $table->id(); // Identifiant unique du colis (Clé primaire)
            $table->string('code_suivi')->unique(); // Code de suivi unique pour le tracking
            $table->string('nom_destinataire'); // Nom du destinataire
            $table->string('prenom_destinataire'); // Prénom du destinataire
            $table->string('telephone_destinataire'); // Téléphone du destinataire
            $table->string('adresse_destinataire'); // Adresse de livraison
            $table->float('poids'); // Poids du colis en kg
            $table->decimal('prix', 10, 2); // Prix du colis (Ex: 150.00 DH)
            
            // Statut actuel du colis dans le processus de livraison
            $table->enum('statut', [
                'enregistre',
                'ramasse',
                'en_cours',
                'livre',
                'retourne',
                'annule'
            ])->default('enregistre');
            
            $table->string('token_suivi')->unique(); // Token sécurisé unique pour le suivi public
            
            // نظام تصفية الأموال (false = باقي ما تصفاش مع الأدمن، true = تصفى)
            $table->boolean('encaissement_admin')->default(false);

            // Relation avec le e-commerçant propriétaire du colis (Clé étrangère)
            $table->foreignId('ecommercant_id')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');
            
            // Relation avec le livreur assigné (Clé étrangère, peut être nulle au début)
            $table->foreignId('livreur_id')
                  ->nullable()
                  ->constrained('utilisateurs')
                  ->onDelete('set null');
                  
            $table->timestamps(); // Colonnes 'created_at' et 'updated_at'

            $table->foreignId('destinataire_id')->nullable()->constrained()->onDelete('set null'); // Relation avec le destinataire (Clé étrangère, peut être nulle)
        });

        // 🔄 كنرجعو الحماية كيف كانت بعد ما تكرا الطابل بنجاح
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Annuler les migrations (Supprimer la table).
     */
    public function down(): void
    {
        Schema::dropIfExists('colis');
    }
};