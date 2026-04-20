<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Piece;
use App\Models\Marque;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $query = Piece::with(['marque', 'category'])
            ->where('is_visible', true);

        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        if ($request->filled('marque')) {
            $query->where('marque_id', $request->marque);
        }

        $pieces = $query->paginate(12);
        
        $categories = Category::where('is_active', true)->get();
        $marques = Marque::where('is_active', true)->get();

        return view('client.catalogue.index', compact('pieces', 'categories', 'marques'));
    }

    public function show($slug)
    {
        $piece = Piece::with(['marque', 'category', 'references' => function($q) {
            $q->where('is_active', true);
        }, 'references.voitures'])->where('slug', $slug)->firstOrFail();

        return view('client.catalogue.show', compact('piece'));
    }

    public function parCategorie($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $pieces = Piece::where('categorie_id', $category->id)->where('is_visible', true)->paginate(12);
        
        return view('client.catalogue.categorie', compact('category', 'pieces'));
    }

    public function recherche(Request $request)
    {
        $q = $request->input('q');
        
        $pieces = Piece::where('is_visible', true)
            ->where(function($query) use ($q) {
                $query->where('nom', 'like', "%{$q}%")
                      ->orWhere('reference_fournisseur', 'like', "%{$q}%")
                      ->orWhereHas('references', function($subQ) use ($q) {
                          $subQ->where('reference', 'like', "%{$q}%")
                               ->orWhere('nom', 'like', "%{$q}%");
                      });
            })
            ->paginate(12);
            
        return view('client.catalogue.index', [
            'pieces' => $pieces,
            'categories' => Category::where('is_active', true)->get(),
            'marques' => Marque::where('is_active', true)->get(),
            'searchQuery' => $q
        ]);
    }

    public function suggestions(Request $request)
    {
        $q = $request->input('q');
        
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $pieces = Piece::where('is_visible', true)
            ->where(function($query) use ($q) {
                $query->where('nom', 'like', "%{$q}%")
                      ->orWhere('reference_fournisseur', 'like', "%{$q}%")
                      ->orWhereHas('references', function($subQ) use ($q) {
                          $subQ->where('reference', 'like', "%{$q}%")
                               ->orWhere('nom', 'like', "%{$q}%");
                      });
            })
            ->with(['marque', 'category'])
            ->limit(8)
            ->get();

        $results = $pieces->map(function($piece) {
            return [
                'id' => $piece->id,
                'nom' => $piece->nom,
                'slug' => $piece->slug,
                'marque' => $piece->marque->nom ?? 'generique',
                'prix' => number_format($piece->prix, 2) . 'DH',
                'image' => (isset($piece->images) && count($piece->images) > 0) ? asset('storage/' . $piece->images[0]) : null,
                'url' => route('client.catalogue.show', $piece->slug)
            ];
        });

        return response()->json($results);
    }
}
