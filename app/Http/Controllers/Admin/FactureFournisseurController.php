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
        return redirect()->route('admin.factures-fournisseur.index')->with('success', 'Facture annulée et archivée avec succès.');
    }
}
