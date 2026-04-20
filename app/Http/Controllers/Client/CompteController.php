<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompteController extends Controller
{
    public function profil()
    {
        return view('client.compte.profil', [
            'user' => auth()->user()
        ]);
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'nom'              => ['required', 'string', 'max:100'],
            'prenom'           => ['required', 'string', 'max:100'],
            'telephone'        => ['nullable', 'string', 'max:20'],
            'adresse_livraison'=> ['nullable', 'string', 'max:255'],
            'code_postal'      => ['nullable', 'string', 'max:10'],
            'ville'            => ['nullable', 'string', 'max:100'],
        ]);
        
        $user->update($validated);
        
        return redirect()->route('client.compte.profil')->with('success', 'Profil mis à jour');
    }
}
