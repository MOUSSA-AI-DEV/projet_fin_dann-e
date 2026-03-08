<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    protected $fillable = [
        'marque',
        'modele',
        'annee_debut',
        'motorisation',
        'puissance',
        'reference_id',
        'immatriculation_type'
    ];

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }
}
