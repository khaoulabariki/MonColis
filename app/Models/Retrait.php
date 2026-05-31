<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retrait extends Model
{
    protected $table = 'retraits';

    protected $fillable = [
        'ecomercant_id',
        'montant',
        'statut',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    // Relations
    public function ecomercant()
    {
        return $this->belongsTo(Utilisateur::class, 'ecomercant_id');
    }
}
