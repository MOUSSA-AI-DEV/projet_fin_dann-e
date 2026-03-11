<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reference;
use App\Models\Piece;
use App\Models\Voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferenceController extends Controller
{
    public function index()
    {
        $references = Reference::with(['piece', 'voitures'])->latest()->paginate(10);
        return view('admin.references.index', compact('references'));
    }

    public function create()
    {
        $pieces = Piece::all();
        $voitures = Voiture::all();
        return view('admin.references.create', compact('pieces', 'voitures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:50|unique:references,reference',
            'nom' => 'required|string|max:100',
            'piece_id' => 'required|exists:pieces,id',
            'description' => 'nullable|string',
            'garantie' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'position' => 'nullable|integer',
            'voiture_ids' => 'nullable|array',
            'voiture_ids.*' => 'exists:voitures,id'
        ]);

        $validated['slug'] = Str::slug($validated['nom']);
        $slug = $validated['slug'];
        $count = 1;
        while (Reference::where('slug', $slug)->exists()) {
            $slug = $validated['slug'] . '-' . $count++;
        }
        $validated['slug'] = $slug;

        $reference = Reference::create($validated);

        if ($request->has('voiture_ids')) {
            $reference->voitures()->sync($request->voiture_ids);
        }

        return redirect()->route('admin.references.index')->with('success', 'Référence créée avec succès.');
    }

    public function show(Reference $reference)
    {
        $reference->load(['piece', 'voitures']);
        return view('admin.references.show', compact('reference'));
    }

    public function edit(Reference $reference)
    {
        $pieces = Piece::all();
        $voitures = Voiture::all();
        return view('admin.references.edit', compact('reference', 'pieces', 'voitures'));
    }

    public function update(Request $request, Reference $reference)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:50|unique:references,reference,' . $reference->id,
            'nom' => 'required|string|max:100',
            'piece_id' => 'required|exists:pieces,id',
            'description' => 'nullable|string',
            'garantie' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'position' => 'nullable|integer',
            'voiture_ids' => 'nullable|array',
            'voiture_ids.*' => 'exists:voitures,id'
        ]);

        if ($validated['nom'] !== $reference->nom) {
            $validated['slug'] = Str::slug($validated['nom']);
            $slug = $validated['slug'];
            $count = 1;
            while (Reference::where('slug', $slug)->where('id', '!=', $reference->id)->exists()) {
                $slug = $validated['slug'] . '-' . $count++;
            }
            $validated['slug'] = $slug;
        }

        $reference->update($validated);

        if ($request->has('voiture_ids')) {
            $reference->voitures()->sync($request->voiture_ids);
        } else {
            $reference->voitures()->detach();
        }

        return redirect()->route('admin.references.index')->with('success', 'Référence mise à jour avec succès.');
    }

    public function destroy(Reference $reference)
    {
        $reference->delete();
        return redirect()->route('admin.references.index')->with('success', 'Référence supprimée avec succès.');
    }
}
