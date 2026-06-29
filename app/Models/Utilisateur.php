<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    // On spécifie le nom de la table car Laravel cherche par défaut 'utilisateurs' au pluriel anglais
    protected $table = 'utilisateurs';

    // Les champs éligibles pour le remplissage de masse (Mass Assignment)
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'telephone',
        'statut',
    ];

    // Masquer le mot de passe et le remember_token dans les requêtes/JSON pour la sécurité
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // --- RELATIONS ---

    // Un utilisateur (e-commerçant) peut avoir plusieurs colis
    public function colisEcommercant()
    {
        return $this->hasMany(Colis::class, 'ecommercant_id');
    }

    // Un utilisateur (livreur) peut être assigné à plusieurs colis
    public function colisLivreur()
    {
        return $this->hasMany(Colis::class, 'livreur_id');
    }

    // Un utilisateur (livreur) peut avoir plusieurs affectations dans l'historique
    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'livreur_id');
    }

    // Un utilisateur peut recevoir plusieurs notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'utilisateur_id');
    }

    // Un utilisateur peut générer plusieurs logs d'audit
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'utilisateur_id');
    }

    public function destinataires()
{
    return $this->hasMany(Destinataire::class, 'utilisateur_id');
}
}