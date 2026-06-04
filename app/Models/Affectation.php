<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
    protected $table = 'affectations';

    protected $fillable = [
        'colis_id',
        'livreur_id',
        'date_affectation',
        'statut',
    ];

    // --- RELATIONS ---

    // L'affectation concerne un colis spécifique
    public function colis()
    {
        return $this->belongsTo(Colis::class, 'colis_id');
    }

    // L'affectation est attribuée à un livreur spécifique
    public function livreur()
    {
        return $this->belongsTo(Utilisateur::class, 'livreur_id');
    }
}