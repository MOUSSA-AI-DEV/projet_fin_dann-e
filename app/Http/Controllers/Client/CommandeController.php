<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Command;
use App\Models\CommandeReference;
use App\Models\Reference;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CommandeController extends Controller
{
    public function valider(Request $request)
    {
        $panier = session()->get('panier', []);
        
        if(empty($panier)) {
            return redirect()->route('client.panier.index')->with('error', 'Votre panier est vide.');
        }

        $total = array_sum(array_map(function($item) {
            return $item['prix'] * $item['quantite'];
        }, $panier));

        DB::beginTransaction();

        try {
            $commande = Command::create([
                'user_id' => auth()->id(),
                'numero_commande' => 'CMD-' . now()->format('Ymd-His'),
                'payment_status' => 'pending',
                'statut' => 'en_attente',
                'total' => $total,
                'notes_client' => $request->notes_client
            ]);

            foreach($panier as $item) {
                CommandeReference::create([
                    'commande_id' => $commande->id,
                    'reference_id' => $item['reference_id'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix'],
                    'total_ligne' => $item['quantite'] * $item['prix'],
                ]);
                
                Reference::where('id', $item['reference_id'])->decrement('stock', $item['quantite']);
            }

            $pdf = Pdf::loadView('pdf.facture', compact('commande'));
            $pdfName = 'facture_' . $commande->numero_commande . '.pdf';
            $pdfPath = 'factures/' . $pdfName;
            
            Storage::disk('public')->put($pdfPath, $pdf->output());
            
            $commande->update(['facture_pdf' => $pdfPath]);

            DB::commit();

            session()->forget('panier');
            
            return redirect()->route('client.commande.confirmation', $commande->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la validation : ' . $e->getMessage());
        }
    }

    public function confirmation($id)
    {
        $commande = Command::where('user_id', auth()->id())->findOrFail($id);
        return view('client.commande.confirmation', compact('commande'));
    }

    public function mesCommandes()
    {
        $commandes = Command::where('user_id', auth()->id())->latest()->get();
        return view('client.commande.mes-commandes', compact('commandes'));
    }

    public function detail($numero)
    {
        $commande = Command::with(['references.reference.piece'])
            ->where('user_id', auth()->id())
            ->where('numero_commande', $numero)
            ->firstOrFail();
            
        return view('client.commande.detail', compact('commande'));
    }
}
