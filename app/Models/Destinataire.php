<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinataire extends Model
{
    protected $fillable = ['utilisateur_id', 'nom', 'prenom', 'telephone', 'adresse', 'ville'];

    
    public function ecommercant()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

  
    public function colis()
    {
        return $this->hasMany(Colis::class);
    }

   
}