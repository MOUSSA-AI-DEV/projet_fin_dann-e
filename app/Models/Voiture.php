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
        'immatriculation_type',
        'caracteristiques',
        'images'
    ];

    protected $casts = [
        'caracteristiques' => 'array',
        'images' => 'array',
    ];

    public function references()
    {
        return $this->belongsToMany(Reference::class, 'reference_voiture');
    }
}
