<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Piece extends Model
{
    protected $fillable = [
        'nom',
        'slug',
        'reference_fournisseur',
        'prix',
        'stock',
        'description',
        'caracteristiques',
        'images',
        'categorie_id',
        'marque_id',
        'is_visible',
        'position'
    ];

    protected $casts = [
        'prix' => 'float',
        'caracteristiques' => 'array',
        'images' => 'array',
        'is_visible' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function marque()
    {
        return $this->belongsTo(Marque::class);
    }

    public function references()
    {
        return $this->hasMany(Reference::class);
    }
}
