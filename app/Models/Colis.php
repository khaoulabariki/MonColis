<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Colis extends Model
{
    protected $table = 'colis';

    protected $fillable = [
        'code_suivi',
        'nom_destinataire',
        'prenom_destinataire',
        'telephone_destinataire',
        'adresse_destinataire',
        'poids',
        'prix',
        'statut',
        'token_suivi',
        'ecomercant_id',
    ];

    // Générer automatiquement code_suivi et token_suivi
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($colis) {
            $colis->code_suivi = 'MC-' . strtoupper(Str::random(8));
            $colis->token_suivi = Str::uuid();
        });
    }

    // Relations
    public function ecomercant()
    {
        return $this->belongsTo(Utilisateur::class, 'ecomercant_id');
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'colis_id');
    }

    public function avis()
    {
        return $this->hasOne(Avis::class, 'colis_id');
    }

    // Livreur actuel
    public function livreurActuel()
    {
        return $this->affectations()->latest()->first()?->livreur;
    }
}