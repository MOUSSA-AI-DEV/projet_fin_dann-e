<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
protected $fillable = ['colocation_id','owner_id','email','token','accepted_at','refused_at'];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function owner()
    
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function scopePending($query)
    {
        return $query->whereNull('accepted_at')
            ->whereNull('refused_at');
    }

}
