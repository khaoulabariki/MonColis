<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colis', function (Blueprint $table) {
            $table->id();
            $table->string('code_suivi')->unique();
            $table->string('nom_destinataire');
            $table->string('prenom_destinataire');
            $table->string('telephone_destinataire');
            $table->string('adresse_destinataire');
            $table->float('poids');
            $table->decimal('prix', 10, 2);
            $table->enum('statut', [
                'enregistre',
                'ramasse',
                'en_cours',
                'livre',
                'retourne',
                'annule'
            ])->default('enregistre');
            $table->string('token_suivi')->unique();
            $table->foreignId('ecomercant_id')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colis');
    }
};