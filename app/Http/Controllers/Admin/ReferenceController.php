<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reference;
use App\Models\Piece;
use App\Models\Voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ReferenceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $query = Reference::with(['piece', 'voitures'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('reference', 'like', "%{$search}%")
                          ->orWhere('nom', 'like', "%{$search}%")
                          ->orWhereHas('piece', fn($q2) =>
                              $q2->where('nom', 'like', "%{$search}%")
                          )
                          ->orWhereHas('voitures', fn($q3) =>
                              $q3->where('marque', 'like', "%{$search}%")
                                 ->orWhere('modele', 'like', "%{$search}%")
                          );
                });
            })
            ->latest();

        $references = $query->paginate(15)->appends(['search' => $search]);

        if ($request->ajax()) {
            return view('admin.references._table', compact('references', 'search'))->render();
        }

        return view('admin.references.index', compact('references', 'search'));
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
            'prix' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
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

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('references', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        $reference = Reference::create($validated);

        if ($request->has('voiture_ids')) {
            $reference->voitures()->sync($request->voiture_ids);
        }

        return redirect()->route('admin.references.index')->with('success', 'reference cree.');
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
            'prix' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'delete_images' => 'nullable|array',
            'voiture_ids' => 'nullable|array',
            'voiture_ids.*' => 'exists:voitures,id'
        ]);

        if ($validated['nom'] !== $reference->nom) {
            $slug = Str::slug($validated['nom']);
            $count = 1;
            while (Reference::where('slug', $slug)->where('id', '!=', $reference->id)->exists()) {
                $slug = $validated['slug'] . '-' . $count++;
            }
            $validated['slug'] = $slug;
        }

        $currentImages = $reference->images ?? [];

        //suppression de page
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imgToDelete) {
                Storage::disk('public')->delete($imgToDelete);
                $currentImages = array_filter($currentImages, fn($img) => $img !== $imgToDelete);
            }
        }

        //telechargement de page
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('references', 'public');
                $currentImages[] = $path;
            }
        }
        $validated['images'] = array_values($currentImages);

        $reference->update($validated);
// mise ajours de compatibilite si exist au mois une voiture 
        if ($request->has('voiture_ids')) {
            $reference->voitures()->sync($request->voiture_ids);
        } else {
            $reference->voitures()->detach();
        }

        return redirect()->route('admin.references.index')->with('success', 'reference mise a jour avec succes.');
    }

    public function destroy(Reference $reference)
    {
        if ($reference->images) {
            foreach ($reference->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $reference->delete();
        return redirect()->route('admin.references.index')->with('success', 'Référence supprimée avec succès.');
    }
}
