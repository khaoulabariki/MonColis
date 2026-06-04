<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'utilisateur_id',
        'action',
        'entite',
        'donnees_avant',
        'donnees_apres',
    ];

    // Cast des colonnes JSON en tableaux PHP automatiquement
    protected $casts = [
        'donnees_avant' => 'array',
        'donnees_apres' => 'array',
    ];

    // --- RELATIONS ---

    // Le log d'audit a été généré par un utilisateur spécifique
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }
}