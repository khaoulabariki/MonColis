<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'wallet_id',
        'type',
        'montant',
        'description',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    // Relations
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
}