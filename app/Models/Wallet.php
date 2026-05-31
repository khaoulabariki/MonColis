<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';

    protected $fillable = [
        'ecomercant_id',
        'solde',
    ];

    protected $casts = [
        'solde' => 'decimal:2',
    ];

    // Relations
    public function ecomercant()
    {
        return $this->belongsTo(Utilisateur::class, 'ecomercant_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'wallet_id');
    }

    // Helpers
    public function debiter($montant)
    {
        $this->solde -= $montant;
        $this->save();

        Transaction::create([
            'wallet_id'   => $this->id,
            'type'        => 'debit',
            'montant'     => $montant,
            'description' => 'Frais de livraison',
        ]);
    }

    public function crediter($montant)
    {
        $this->solde += $montant;
        $this->save();

        Transaction::create([
            'wallet_id'   => $this->id,
            'type'        => 'credit',
            'montant'     => $montant,
            'description' => 'Paiement livraison',
        ]);
    }
}
