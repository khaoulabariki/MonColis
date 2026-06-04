<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'ecommercant_id',
        'livreur_id',
    ];

    // --- RELATIONS ---

    // Le colis appartient à un e-commerçant
    public function ecommercant()
    {
        return $this->belongsTo(Utilisateur::class, 'ecommercant_id');
    }

    // Le colis peut être assigné à un livreur (optionnel)
    public function livreur()
    {
        return $this->belongsTo(Utilisateur::class, 'livreur_id');
    }

    // Un colis peut avoir plusieurs affectations (historique de ses livreurs)
    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'colis_id');
    }

    // Un colis peut avoir un ou plusieurs avis (selon ton MCD)
    public function avis()
    {
        return $this->hasMany(Avis::class, 'colis_id');
    }
}