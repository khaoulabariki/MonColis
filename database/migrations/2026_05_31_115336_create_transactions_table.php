<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations pour la création de la table 'transactions'.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // Équivalent à id_transaction (Clé primaire auto-incrémentée)
            
            // Relation avec le portefeuille (Wallet) concerné (Clé étrangère)
            $table->foreignId('wallet_id')
                  ->constrained('wallets')
                  ->onDelete('cascade');
            
            $table->enum('type', ['debit', 'credit']); // Type de transaction : débit (retrait/sortie) ou crédit (entrée)
            $table->decimal('montant', 10, 2); // Montant de la transaction (Ex: 100.50 DH)
            $table->string('description')->nullable(); // Description ou motif de la transaction (Ex: 'Livraison colis #123')
            
            $table->timestamps(); // Gère 'created_at' (équivalent à date_creation) et 'updated_at'
        });
    }

    /**
     * Annuler les migrations (Supprimer la table).
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};