<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandeReference extends Model
{
    protected $table = 'commande_references';
    protected $fillable = [
        'commande_id',
        'reference_id',
        'quantite',
        'prix_unitaire',
        'total_ligne'
    ];

    public function commande()
    {
        return $this->belongsTo(Command::class, 'commande_id');
    }

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }
}
