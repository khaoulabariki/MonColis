<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'role',
        'statut',
        'mot_de_passe', // <--- Trés important 
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    // Pour l'authentification Laravel
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }
}