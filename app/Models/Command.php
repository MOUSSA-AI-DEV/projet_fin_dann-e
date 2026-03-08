<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    protected $fillable = [
        'user_id',
        'numero_commande',
        'payment_status',
        'payment_method',
        'payment_id',
        'facture_pdf',
        'notes_client'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
