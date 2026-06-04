<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';

    protected $fillable = [
        'ecommercant_id',
        'solde',
    ];

    // --- RELATIONS ---

    // Le wallet appartient à un e-commerçant spécifique
    public function ecommercant()
    {
        return $this->belongsTo(Utilisateur::class, 'ecommercant_id');
    }

    // Un wallet peut avoir plusieurs transactions (historique financier)
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'wallet_id');
    }
}