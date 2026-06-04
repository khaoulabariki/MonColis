<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retrait extends Model
{
    protected $table = 'retraits';

    protected $fillable = [
        'ecommercant_id',
        'montant',
        'statut',
    ];

    // --- RELATIONS ---

    // La demande de retrait appartient à un e-commerçant
    public function ecommercant()
    {
        return $this->belongsTo(Utilisateur::class, 'ecommercant_id');
    }
}