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

    // Relations
    public function colis()
    {
        return $this->belongsTo(Colis::class, 'colis_id');
    }
}
