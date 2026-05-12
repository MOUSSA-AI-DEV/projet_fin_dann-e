<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactureFournisseur extends Model
{
    protected $table = 'factures_fournisseur';

    protected $fillable = [
        'numero',
        'fournisseur',
        'date_facture',
        'taux_conversion',
        'coefficient_charges',
        'coefficient_beneficiaire',
        'total_achat_euro',
        'total_achat_mad',
        'total_vente',
        'fichier_original',
        'notes',
    ];

    protected $casts = [
        'date_facture'             => 'date',
        'taux_conversion'          => 'float',
        'coefficient_charges'      => 'float',
        'coefficient_beneficiaire' => 'float',
        'total_achat_euro'         => 'float',
        'total_achat_mad'          => 'float',
        'total_vente'              => 'float',
    ];

    public function references()
    {
        return $this->hasMany(Reference::class, 'facture_fournisseur_id');
    }

    // Marge totale = total_vente - total_achat_mad
    public function getMargeTotaleAttribute(): float
    {
        return $this->total_vente - $this->total_achat_mad;
    }
}
