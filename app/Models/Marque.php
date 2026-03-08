<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
    protected $fillable = [
        'nom',
        'logo_url',
        'is_active'
    ];

    public function pieces()
    {
        return $this->hasMany(Piece::class);
    }
}
