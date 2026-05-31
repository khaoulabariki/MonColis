<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colis_id')
                  ->constrained('colis')
                  ->onDelete('cascade');
            $table->integer('note')->between(1, 5);
            $table->text('commentaire')->nullable();
            $table->enum('sentiment', ['positif', 'neutre', 'negatif'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};

