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

    public function getPmpAttribute()
    {
        // On récupère toutes les entrées pour cette même référence
        $records = self::where('reference', $this->reference)->get();
        
        $valeurTotale = 0;
        $quantiteTotale = 0;

        foreach ($records as $record) {
            $valeurTotale += ($record->prix_achat * $record->stock);
            $quantiteTotale += $record->stock;
        }

        if ($quantiteTotale > 0) {
            return $valeurTotale / $quantiteTotale;
        }

        return $this->prix_achat; // Par défaut si stock à zéro
    }

    public function getPrixRevientAttribute()
    {
        $pa = $this->pmp; // On utilise le PMP au lieu du prix d'achat simple
        $cc = $this->coefficient_charges ?: 0;
        return $pa * (1 + $cc);
    }

    public function getBeneficeAttribute()
    {
        $pa = $this->pmp; // On utilise le PMP pour le calcul du bénéfice
        $cb = $this->coefficient_beneficiaire ?: 0;
        return $pa * $cb;
    }

    public function getPrixVenteAttribute()
    {
        return $this->prix_revient + $this->benefice;
    }

    public function getPrixTtcAttribute()
    {
        return $this->prix_vente * 1.2;
    }
}
