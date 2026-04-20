<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Piece;

class AccueilController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->whereNull('parent_id')->orderBy('position')->take(8)->get();
        $nouveautes = Piece::where('is_visible', true)
            ->with(['references' => function ($query) {
                $query->where('is_active', true)->orderBy('stock', 'asc');
            }, 'marque', 'category'])
            ->latest()
            ->take(8)
            ->get();
            
        return view('client.accueil', compact('categories', 'nouveautes'));
    }
}
