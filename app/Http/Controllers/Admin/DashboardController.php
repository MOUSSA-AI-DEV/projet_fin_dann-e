<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Command;
use App\Models\Piece;
use App\Models\User;
use App\Models\Reference;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Commandes aujourd'hui (pending / paid / failed)
       
        $commandesPendingToday = Command::whereDate('created_at', today())
            ->where('payment_status', 'pending')
            ->count();
            
        $commandesPaidToday = Command::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->count();
            
        $commandesFailedToday = Command::whereDate('created_at', today())
            ->where('payment_status', 'refunded')
            ->count();
            
        // 2. Chiffre d'affaires (24h / 7j / 30j) basé sur les lignes paid
        
        $ca24h = DB::table('commande_references')
            ->join('commandes', 'commande_references.commande_id', '=', 'commandes.id')
            ->where('commandes.payment_status', 'paid')
            ->where('commandes.created_at', '>=', now()->subDay())
            ->sum('commande_references.total_ligne');
            
        $ca7j = DB::table('commande_references')
            ->join('commandes', 'commande_references.commande_id', '=', 'commandes.id')
            ->where('commandes.payment_status', 'paid')
            ->where('commandes.created_at', '>=', now()->subDays(7))
            ->sum('commande_references.total_ligne');
            
        $ca30j = DB::table('commande_references')
            ->join('commandes', 'commande_references.commande_id', '=', 'commandes.id')
            ->where('commandes.payment_status', 'paid')
            ->where('commandes.created_at', '>=', now()->subDays(30))
            ->sum('commande_references.total_ligne');

        // 3. Pièces en stock critique (< 50)
        $piecesStockCritique = Piece::where('stock', '<', 50)->get();
        $countStockCritique = $piecesStockCritique->count();
        
        // 4. Nouveaux utilisateurs de ces 30 derniers jours
        $nouveauxUtilisateurs = User::where('created_at', '>=', now()->subDays(30))->count();
        
        // 5. Top 10 séries (références) vendues
        $topSeries = DB::table('commande_references')
            ->join('references', 'commande_references.reference_id', '=', 'references.id')
            ->select('references.id', 'references.nom', 'references.reference', DB::raw('SUM(commande_references.quantite) as total_vendu'))
            ->groupBy('references.id', 'references.nom', 'references.reference')
            ->orderByDesc('total_vendu')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'commandesPendingToday',
            'commandesPaidToday',
            'commandesFailedToday',
            'ca24h',
            'ca7j',
            'ca30j',
            'piecesStockCritique',
            'countStockCritique',
            'nouveauxUtilisateurs',
            'topSeries'
        ));
    }
}
