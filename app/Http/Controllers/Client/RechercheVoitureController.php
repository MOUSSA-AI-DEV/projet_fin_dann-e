<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Voiture;
use App\Models\Reference;
use Illuminate\Http\Request;

class RechercheVoitureController extends Controller
{
    public function index()
    {
        $marques = Voiture::select('marque')->distinct()->orderBy('marque')->pluck('marque');
        return view('client.recherche-voiture.index', compact('marques'));
    }

    public function getModeles(Request $request)
    {
        $modeles = Voiture::where('marque', $request->marque)
            ->select('modele')
            ->distinct()
            ->orderBy('modele')
            ->pluck('modele');
            
        return response()->json($modeles);
    }

    public function getMotorisations(Request $request)
    {
        $motorisations = Voiture::where('marque', $request->marque)
            ->where('modele', $request->modele)
            ->select('motorisation')
            ->distinct()
            ->orderBy('motorisation')
            ->pluck('motorisation');
            
        return response()->json($motorisations);
    }

    public function resultats(Request $request)
    {
        $voitures = Voiture::where('marque', $request->marque)
            ->where('modele', $request->modele);
            
        if ($request->filled('motorisation')) {
            $voitures->where('motorisation', $request->motorisation);
        }
        
        $voitureIds = $voitures->pluck('id');
        
        $references = Reference::with(['piece.marque', 'piece.category'])
            ->where('is_active', true)
            ->whereHas('voitures', function($query) use ($voitureIds) {
                $query->whereIn('voiture_id', $voitureIds);
            })
            ->paginate(12);
            
        return view('client.recherche-voiture.resultats', [
            'references' => $references,
            'search' => $request->all()
        ]);
    }
}
