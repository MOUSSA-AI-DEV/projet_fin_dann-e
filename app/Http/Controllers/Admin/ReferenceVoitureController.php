<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reference;
use App\Models\Voiture;
use Illuminate\Http\Request;

class ReferenceVoitureController extends Controller
{
    public function attachVoiture(Request $request, Reference $reference)
    {
        $request->validate([
            'voiture_id' => 'required|exists:voitures,id'
        ]);

        $reference->voitures()->syncWithoutDetaching([$request->voiture_id]);

        return back()->with('success', 'Voiture associee avec succes.');
    }

    public function detachVoiture(Reference $reference, Voiture $voiture)
    {
        $reference->voitures()->detach($voiture->id);

        return back()->with('success', 'Association supprimee avec succes.');
    }
}
