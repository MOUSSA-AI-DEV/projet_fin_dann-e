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
        'hs_code',
        'origine',
        'garantie',
        'is_active',
        'position',
        'stock',
        'prix_achat',
        'coefficient_charges',
        'coefficient_beneficiaire',
        'prix',
        'images',
        'facture_fournisseur_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'prix_achat' => 'float',
        'coefficient_charges' => 'float',
        'coefficient_beneficiaire' => 'float',
        'prix' => 'float',
        'images' => 'array',
    ];

    public function piece()
    {
        return $this->belongsTo(Piece::class);
    }

    public function factureFournisseur()
    {
        return $this->belongsTo(FactureFournisseur::class, 'facture_fournisseur_id');
    }

    public function voitures()
    {
        return $this->belongsToMany(Voiture::class, 'reference_voiture');
    }

    public function commandes()
    {
        return $this->belongsToMany(Command::class, 'commande_reference');
    }

    // Accessors for price calculations

    public function getPrixRevientAttribute()
    {
        $pa = $this->prix_achat ?: 0;
        $cc = $this->coefficient_charges ?: 0;
        return $pa * (1 + $cc);
    }

    public function getBeneficeAttribute()
    {
        return $this->prix_revient * ($this->coefficient_beneficiaire ?: 0);
    }

    public function getPrixVenteAttribute()
    {
        return $this->prix_revient + $this->benefice;
    }
}
