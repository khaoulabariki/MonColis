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

    protected $casts = [
        'date_affectation' => 'date',
    ];

    // Relations
    public function colis()
    {
        return $this->belongsTo(Colis::class, 'colis_id');
    }

    public function livreur()
    {
        return $this->belongsTo(Utilisateur::class, 'livreur_id');
    }
}