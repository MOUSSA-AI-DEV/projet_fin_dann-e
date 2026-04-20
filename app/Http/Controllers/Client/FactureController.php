<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Command;

class FactureController extends Controller
{
    public function download(Command $commande)
    {
        if ($commande->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        if (!$commande->facture_pdf) {
            abort(404, 'Facture introuvable');
        }

        $pdfPath = storage_path('app/public/' . $commande->facture_pdf);
        
        if (!file_exists($pdfPath)) {
            abort(404, 'Fichier PDF introuvable');
        }
        
        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Facture-' . $commande->numero_commande . '.pdf"'
        ]);
    }
}
