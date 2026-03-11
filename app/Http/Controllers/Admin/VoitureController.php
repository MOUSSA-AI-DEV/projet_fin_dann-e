<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voiture;
use Illuminate\Http\Request;

class VoitureController extends Controller
{
    public function index()
    {
        $voitures = Voiture::latest()->paginate(10);
        return view('admin.voitures.index', compact('voitures'));
    }

    public function create()
    {
        return view('admin.voitures.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'marque' => 'required|string|max:50',
            'modele' => 'required|string|max:100',
            'annee_debut' => 'required|integer|min:1900|max:' . date('Y'),
            'motorisation' => 'required|string|max:50',
            'puissance' => 'required|integer|min:1',
            'immatriculation_type' => 'nullable|string|max:20'
        ]);

        Voiture::create($validated);

        return redirect()->route('admin.voitures.index')->with('success', 'Voiture ajoutée avec succès.');
    }

    public function show(Voiture $voiture)
    {
        return view('admin.voitures.show', compact('voiture'));
    }

    public function edit(Voiture $voiture)
    {
        return view('admin.voitures.edit', compact('voiture'));
    }

    public function update(Request $request, Voiture $voiture)
    {
        $validated = $request->validate([
            'marque' => 'required|string|max:50',
            'modele' => 'required|string|max:100',
            'annee_debut' => 'required|integer|min:1900|max:' . date('Y'),
            'motorisation' => 'required|string|max:50',
            'puissance' => 'required|integer|min:1',
            'immatriculation_type' => 'nullable|string|max:20'
        ]);

        $voiture->update($validated);

        return redirect()->route('admin.voitures.index')->with('success', 'Voiture mise à jour avec succès.');
    }

    public function destroy(Voiture $voiture)
    {
        $voiture->delete();
        return redirect()->route('admin.voitures.index')->with('success', 'Voiture supprimée avec succès.');
    }
}
