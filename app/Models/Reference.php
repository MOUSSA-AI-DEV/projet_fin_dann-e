<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
        'reference',
        'nom',
        'slug',
        'piece_id',
        'description',
        'garantie',
        'is_active',
        'position',
        'stock',
        'prix'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'prix' => 'float',
    ];

    public function piece()
    {
        return $this->belongsTo(Piece::class);
    }

    public function voitures()
    {
        return $this->belongsToMany(Voiture::class, 'reference_voiture');
    }
}
