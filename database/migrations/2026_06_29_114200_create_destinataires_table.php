<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('destinataires', function (Blueprint $table) {
        $table->id();
       
        $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade'); 
        
        $table->string('nom');
        $table->string('prenom');
        $table->string('telephone');
        $table->text('adresse');
        $table->string('ville');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinataires');
    }
};
