<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marque;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarqueController extends Controller
{
    public function index()
    {
        $marques = Marque::latest()->paginate(10);
        return view('admin.marques.index', compact('marques'));
    }

    public function create()
    {
        return view('admin.marques.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:50|unique:marques,nom',
            'logo_url' => 'nullable|url|max:255',
            'is_active' => 'boolean'
        ]);

        Marque::create($validated);

        return redirect()->route('admin.marques.index')->with('success', 'Marque créée avec succès.');
    }

    public function show(Marque $marque)
    {
        return view('admin.marques.show', compact('marque'));
    }

    public function edit(Marque $marque)
    {
        return view('admin.marques.edit', compact('marque'));
    }

    public function update(Request $request, Marque $marque)
    {
        $validated = $request->validate([
            
            'nom' => 'required|string|max:50|unique:marques,nom,' . $marque->id,
            'logo_url' => 'nullable|url|max:255',
            'is_active' => 'boolean'
        ]);

        $marque->update($validated);

        return redirect()->route('admin.marques.index')->with('success', 'Marque mise a jour.');
    }

    public function destroy(Marque $marque)
    {
        $marque->delete();
        return redirect()->route('admin.marques.index')->with('success', 'Marque supprimee.');
    }
}
