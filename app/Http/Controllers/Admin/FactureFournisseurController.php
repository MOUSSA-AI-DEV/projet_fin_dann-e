<?php

namespace App\Http\Controllers\Admin;

use App\Models\FactureFournisseur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FactureFournisseurController extends Controller
{
    public function index()
    {
        $factures = FactureFournisseur::withCount('references')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.factures-fournisseur.index', compact('factures'));
    }

    public function show(FactureFournisseur $factures_fournisseur)
    {
        $facture = $factures_fournisseur;
        $facture->load('references.piece');
        return view('admin.factures-fournisseur.show', compact('facture'));
    }

    public function destroy($id)
    {
        $facture = FactureFournisseur::findOrFail($id);
        $facture->status = 'annulée';
        $facture->save();

        // Facultatif : On peut aussi désactiver les références liées
        $facture->references()->update(['is_active' => false]);

        return redirect()->route('admin.factures-fournisseur.index')->with('success', 'Facture annulée et références associées désactivées.');
    }

    public function reactivate($id)
    {
        $facture = FactureFournisseur::findOrFail($id);
        $facture->status = 'valide';
        $facture->save();

        // Réactiver les références liées
        $facture->references()->update(['is_active' => true]);

        return redirect()->route('admin.factures-fournisseur.index')->with('success', 'Facture réactivée et références associées activées.');
    }
}
