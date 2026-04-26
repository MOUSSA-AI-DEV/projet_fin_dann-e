<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reference;

class PanierController extends Controller
{
    public function index()
    {
        $panier = session()->get('panier', []);
        $total = array_sum(array_map(function($item) {
            return $item['prix'] * $item['quantite'];
        }, $panier));
        
        return view('client.panier.index', compact('panier', 'total'));
    }

    public function ajouter(Request $request)
    {
        $reference = Reference::with('piece')->findOrFail($request->reference_id);
        
        $panier = session()->get('panier', []);
        
        if(isset($panier[$reference->id])) {
            $panier[$reference->id]['quantite'] += $request->quantite ?? 1;
        } else {
            $panier[$reference->id] = [
                'reference_id' => $reference->id,
                'code' => $reference->reference,
                'nom' => $reference->nom,
                'piece_nom' => $reference->piece->nom,
                'prix' => $reference->prix ?? $reference->piece->prix,
                'quantite' => $request->quantite ?? 1,
                'image_url' => ($reference->images && count($reference->images) > 0) 
                               ? $reference->images[0] 
                               : ($reference->piece->images[0] ?? null)
            ];
        }
        
        session()->put('panier', $panier);
        
        return redirect()->back()->with('success', 'element ajouter au panier');
    }

    public function modifier(Request $request)
    {
        if($request->reference_id && $request->quantite){
            $panier = session()->get('panier');
            $panier[$request->reference_id]['quantite'] = $request->quantite;
            session()->put('panier', $panier);
            session()->flash('success', 'element modifier dans le panier');
        }
        return redirect()->back();
    }

    public function supprimer($id)
    {
        if($id) {
            $panier = session()->get('panier');
            if(isset($panier[$id])) {
                unset($panier[$id]);
                session()->put('panier', $panier);
            }
            
            session()->flash('success', 'element retirer du panier');
        }
        return redirect()->back();
    }

    public function compter()
    {
        $panier = session()->get('panier', []);
        return response()->json(['count' => count($panier)]);
    }
}
