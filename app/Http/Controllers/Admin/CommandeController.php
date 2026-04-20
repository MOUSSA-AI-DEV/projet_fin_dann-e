<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Command;
use App\Models\User;
use App\Models\Reference;
use App\Models\CommandeReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommandeController extends Controller
{
    public function index(Request $request)
    {
        $query = Command::with('user')->latest();

        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('numero_commande', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($sq) use ($request) {
                      $sq->where('nom', 'like', '%' . $request->search . '%')
                        ->orWhere('prenom', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $commandes = $query->paginate(15);

        return view('admin.commandes.index', compact('commandes'));
    }

    public function create()
    {
        $users = User::orderBy('nom')->get();
        $references = Reference::with('piece')->where('stock', '>', 0)->get();
        
        return view('admin.commandes.create', compact('users', 'references'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            
            'notes_client' => 'nullable|string',
            'statut' => 'required|string',
            'payment_status' => 'required|string',
            
            'references' => 'required|array|min:1',
            'references.*.id' => 'required|exists:references,id',
            'references.*.quantite' => 'required|integer|min:1',
            'references.*.prix' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $userId = $validated['user_id'];
            $user = User::findOrFail($userId);

            $total = 0;
            $lignes = [];

            foreach ($validated['references'] as $refData) {
                $reference = Reference::with('piece')->findOrFail($refData['id']);
                
                if ($reference->stock < $refData['quantite']) {
                    throw new \Exception("Stock insuffisant pour la référence : {$reference->reference}");
                }

                $prixUnitaire = $refData['prix'];
                $totalLigne = $prixUnitaire * $refData['quantite'];
                $total += $totalLigne;

                $lignes[] = [
                    'reference_id' => $reference->id,
                    'quantite' => $refData['quantite'],
                    'prix_unitaire' => $prixUnitaire,
                    'total_ligne' => $totalLigne,
                ];
                
                $reference->decrement('stock', $refData['quantite']);
            }

            $commande = Command::create([
                'user_id' => $userId,
                'numero_commande' => 'CMD-' . now()->format('Ymd-His'),
                'payment_status' => $validated['payment_status'],
                'statut' => $validated['statut'],
                'notes_client' => $validated['notes_client'],
                'total' => $total,
            ]);


            foreach ($lignes as $ligne) {
                $ligne['commande_id'] = $commande->id;
                CommandeReference::create($ligne);
            }

           
            $commande->load(['user', 'references.reference.piece']);
            $pdf = Pdf::loadView('pdf.facture', compact('commande'));
            $pdfName = 'facture_' . $commande->numero_commande . '.pdf';
            $pdfPath = 'factures/' . $pdfName;
            
            Storage::disk('public')->put($pdfPath, $pdf->output());
            
            $commande->update(['facture_pdf' => $pdfPath]);

            DB::commit();

            return redirect()->route('admin.commandes.show', $commande)->with('success', 'Commande créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la création de la commande : ' . $e->getMessage());
        }
    }

    public function show(Command $commande)
    {
        $commande->load(['user', 'references.reference.piece']);
        return view('admin.commandes.show', compact('commande'));
    }

    
    public function update(Request $request, Command $commande)
    {
        $validated = $request->validate([
            'statut' => 'required|string',
            'payment_status' => 'required|string',
        ]);

        $commande->update($validated);

        return redirect()->back()->with('success', 'La commande a été mise à jour avec succès.');
    }

    /**
     * Show the invoice PDF.
     */
    public function showFacture(Command $commande)
    {
        if (!$commande->facture_pdf) {
            abort(404, 'Facture non générée.');
        }

        // Nettoyer le chemin si nécessaire (cas des anciennes commandes avec "storage/")
        $path = $commande->facture_pdf;
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Le fichier PDF est introuvable sur le disque.');
        }

        return Storage::disk('public')->response($path);
    }
}
