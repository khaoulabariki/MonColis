<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affectations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colis_id')
                  ->constrained('colis')
                  ->onDelete('cascade');
            $table->foreignId('livreur_id')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');
            $table->date('date_affectation');
            $table->enum('statut', ['en_attente', 'en_cours', 'termine'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};