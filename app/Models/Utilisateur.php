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
        'mot_de_passe',
        'role',
        'telephone',
        'statut',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    protected function casts(): array
    {
        return [
            'mot_de_passe' => 'hashed',
            'statut' => 'boolean',
        ];
    }

    // Relations
    public function colis()
    {
        return $this->hasMany(Colis::class, 'ecomercant_id');
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'livreur_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'ecomercant_id');
    }

    public function retraits()
    {
        return $this->hasMany(Retrait::class, 'ecomercant_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'utilisateur_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'utilisateur_id');
    }

    // Helpers
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEcomercant()
    {
        return $this->role === 'ecomercant';
    }

    public function isLivreur()
    {
        return $this->role === 'livreur';
    }
}