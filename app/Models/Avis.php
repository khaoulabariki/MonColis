<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $table = 'avis';

    protected $fillable = [
        'colis_id',
        'note',
        'commentaire',
        'sentiment',
    ];

    // --- RELATIONS ---

    // L'avis concerne un colis spécifique
    public function colis()
    {
        return $this->belongsTo(Colis::class, 'colis_id');
    }
}