<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Piece;
use App\Models\Category;
use App\Models\Marque;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PieceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $query = Piece::with(['category', 'marque'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('nom', 'like', "%{$search}%")
                        ->orWhere('reference_fournisseur', 'like', "%{$search}%")
                        ->orWhereHas('category', fn($q2) => $q2->where('nom', 'like', "%{$search}%"))
                        ->orWhereHas('marque', fn($q3) => $q3->where('nom', 'like', "%{$search}%"));
                });
            })
            ->latest();

        $pieces = $query->paginate(10)->appends(['search' => $search]);

        if ($request->ajax()) {
            return view('admin.pieces._table', compact('pieces'));
        }

        return view('admin.pieces.index', compact('pieces', 'search'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $marques = Marque::where('is_active', true)->get();
        return view('admin.pieces.create', compact('categories', 'marques'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'reference_fournisseur' => 'nullable|string|max:50',
            'prix' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'marque_id' => 'required|exists:marques,id',
            'is_visible' => 'boolean',
            'position' => 'nullable|integer'
        ]);

        $slug = Str::slug($validated['nom']);
        $count = 1;
        while (Piece::where('slug', $slug)->exists()) {
            $slug = $validated['slug'] . '-' . $count++;
        }
        $validated['slug'] = $slug;

        Piece::create($validated);

        return redirect()->route('admin.pieces.index')->with('success', 'piece cree.');
    }

    public function show(Piece $piece)
    {
        $piece->load(['category', 'marque', 'references.voitures']);
        return view('admin.pieces.show', compact('piece'));
    }

    public function edit(Piece $piece)
    {
        $categories = Category::where('is_active', true)->get();
        $marques = Marque::where('is_active', true)->get();
        return view('admin.pieces.edit', compact('piece', 'categories', 'marques'));
    }

    public function update(Request $request, Piece $piece)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'reference_fournisseur' => 'nullable|string|max:50',
            'prix' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'marque_id' => 'required|exists:marques,id',
            'is_visible' => 'boolean',
            'position' => 'nullable|integer'
        ]);

        if ($validated['nom'] !== $piece->nom) {
           $slug = Str::slug($validated['nom']);
            $count = 1;
            // verifier si slug existe
            while (Piece::where('slug', $slug)->where('id', '!=', $piece->id)->exists()) {
                $slug = $validated['slug'] . '-' . $count++;
            }
            $validated['slug'] = $slug;
        }

        $piece->update($validated);

        return redirect()->route('admin.pieces.index')->with('success', 'Pièce mise à jour avec succès.');
    }

    public function destroy(Piece $piece)
    {
        $piece->delete();
        return redirect()->route('admin.pieces.index')->with('success', 'Pièce supprimée avec succès.');
    }
}
