<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'utilisateur_id',
        'message',
        'lu',
    ];

    // --- RELATIONS ---

    // La notification appartient à un utilisateur spécifique (admin, livreur, e-commerçant)
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }
}