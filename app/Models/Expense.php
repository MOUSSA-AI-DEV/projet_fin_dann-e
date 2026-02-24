<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['colocation_id','categorie_id','payer_id','title','amount','date'];

    public function colocation(){
        return $this->belongsTo(Colocation::class);
    }

    public function category(){
        return $this->belongsTo(category::class);

    }
    public function payer()
    {
        return $this->belongsTo(user::class, 'payer_id');
    }
}
