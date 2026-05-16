<?php

namespace App\Imports;

use App\Models\Reference;
use App\Models\Piece;
use App\Models\Category;
use App\Models\Marque;
use App\Models\Setting;
use App\Models\FactureFournisseur;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ReferenceImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    protected $facture;
    public $totalAchatEuro = 0;
    public $totalAchatMad = 0;
    public $totalVente = 0;

    public function __construct(FactureFournisseur $facture = null)
    {
        $this->facture = $facture;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Settings from Facture or Global
        $coeffBenef = $this->facture ? $this->facture->coefficient_beneficiaire : (Setting::where('key', 'global_coefficient')->value('value') ?? 0.20);
        $coeffCharges = $this->facture ? $this->facture->coefficient_charges : (Setting::where('key', 'global_coeff_charges')->value('value') ?? 0.10);
        $tauxConversion = $this->facture ? $this->facture->taux_conversion : 11;

        // 1. Marque (using 'Sigle' or from Excel)
        $marqueName = $row['sigle'] ?? $row['marque'] ?? $row['ori'] ?? 'AUTRE';
        $marque = Marque::firstOrCreate(
            ['nom' => $marqueName],
            ['is_active' => true]
        );

        // 2. Category (Defaulting to 'Pièces Détachées' if not in Excel)
        $categoryName = $row['categorie'] ?? 'Pièces Détachées';
        $category = Category::firstOrCreate(
            ['nom' => $categoryName],
            [
                'slug' => Str::slug($categoryName),
                'is_active' => true
            ]
        );

        // 3. Piece (using 'Nom', 'Description' or 'piece')
        $pieceName = $row['nom'] ?? $row['piece'] ?? $row['description'] ?? 'Produit sans nom';
        $piece = Piece::firstOrCreate(
            ['nom' => $pieceName],
            [
                'slug' => Str::slug($pieceName),
                'categorie_id' => $category ? $category->id : null,
                'marque_id' => $marque ? $marque->id : null,
                'is_visible' => true
            ]
        );

        // 4. Price Calculation
        // Clean the price string (remove €, $, and spaces)
        $rawPrice = $row['prix_vente'] ?? $row['prix_unitaire'] ?? $row['prix'] ?? 0;
        if (is_string($rawPrice)) {
            $rawPrice = preg_replace('/[^0-9.,]/', '', $rawPrice);
            $rawPrice = str_replace(',', '.', $rawPrice);
        }
        
        $prixAchatEuro = floatval($rawPrice);
        $prixAchatMad = $prixAchatEuro * $tauxConversion;
        $quantite = intval($row['qte_fac'] ?? $row['quantite'] ?? $row['stock'] ?? 0);

        // 5. Reference
        $referenceCode = $row['code_article'] ?? $row['references'] ?? $row['reference'] ?? $row['ref'] ?? $row['oem'] ?? null;

        if (!$referenceCode) {
            return null;
        }

        // Rechercher par code de référence ET par facture pour autoriser les doublons sur des factures différentes
        $reference = Reference::where('reference', $referenceCode);
        
        if ($this->facture) {
            $reference->where('facture_fournisseur_id', $this->facture->id);
        }

        $reference = $reference->firstOrNew([
            'reference' => $referenceCode,
            'facture_fournisseur_id' => $this->facture ? $this->facture->id : null
        ]);

        $reference->nom = $pieceName;
        if (!$reference->exists) {
            $invoiceSuffix = $this->facture ? '-' . $this->facture->id : '';
            $reference->slug = Str::slug($pieceName . '-' . $referenceCode . $invoiceSuffix);
        }
        $reference->piece_id = $piece->id;
        
        // Mettre à jour les infos si elles sont fournies, sinon garder les existantes
        $reference->description = $row['description'] ?? $row['nom'] ?? $reference->description;
        $reference->hs_code = $row['stat_douani'] ?? $row['hs_code'] ?? $reference->hs_code;
        $reference->origine = $row['ori'] ?? $row['origine'] ?? $reference->origine;
        
        // Ajout au stock existant
        if ($reference->exists) {
            $reference->stock += $quantite;
        } else {
            $reference->stock = $quantite;
        }

        $reference->prix_achat = $prixAchatMad;
        $reference->coefficient_charges = $coeffCharges;
        $reference->coefficient_beneficiaire = $coeffBenef;
        $reference->is_active = true;
        
        // Lier à la nouvelle facture
        if ($this->facture) {
            $reference->facture_fournisseur_id = $this->facture->id;
        }

        // Calculate final prix (vente)
        $prixVente = $reference->prix_vente;
        $reference->prix = $prixVente;

        // Accumulate totals for the facture
        $this->totalAchatEuro += ($prixAchatEuro * $quantite);
        $this->totalAchatMad += ($prixAchatMad * $quantite);
        $this->totalVente += ($prixVente * $quantite);

        return $reference;
    }

    public function rules(): array
    {
        return [
            'references' => 'nullable',
            'reference'  => 'nullable',
            'sigle'      => 'nullable',
            'marque'     => 'nullable',
            'hs_code'    => 'nullable',
            'origine'    => 'nullable',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
