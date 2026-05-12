<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reference;
use App\Models\Piece;
use App\Models\Voiture;
use App\Models\Setting;
use App\Models\FactureFournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Imports\ReferenceImport;
use Maatwebsite\Excel\Facades\Excel;


class ReferenceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $query = Reference::with(['piece', 'voitures'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('reference', 'like', "%{$search}%")
                          ->orWhere('nom', 'like', "%{$search}%")
                          ->orWhereHas('piece', fn($q2) =>
                              $q2->where('nom', 'like', "%{$search}%")
                                 ->orWhereHas('marque', fn($q_m) => 
                                     $q_m->where('nom', 'like', "%{$search}%")
                                 )
                          )
                          ->orWhereHas('voitures', fn($q3) =>
                              $q3->where('marque', 'like', "%{$search}%")
                                 ->orWhere('modele', 'like', "%{$search}%")
                          );
                });
            })
            ->latest();

        $references = $query->paginate(15)->appends(['search' => $search]);

        $globalCoeff = Setting::where('key', 'global_coefficient')->value('value') ?? 0.20;
        $globalCoeffCharges = Setting::where('key', 'global_coeff_charges')->value('value') ?? 0.10;

        if ($request->ajax()) {
            return view('admin.references._table', compact('references', 'search'))->render();
        }

        return view('admin.references.index', compact('references', 'search', 'globalCoeff', 'globalCoeffCharges'));
    }

    public function updateGlobalPricing(Request $request)
    {
        $validated = $request->validate([
            'coefficient' => 'required|numeric|min:0',
            'coeff_charges' => 'required|numeric|min:0',
            'apply_to_all' => 'boolean'
        ]);

        Setting::updateOrCreate(['key' => 'global_coefficient'], ['value' => $validated['coefficient']]);
        Setting::updateOrCreate(['key' => 'global_coeff_charges'], ['value' => $validated['coeff_charges']]);

        if ($request->has('apply_to_all') && $request->apply_to_all) {
            Reference::query()->update([
                'coefficient_beneficiaire' => $validated['coefficient'],
                'coefficient_charges' => $validated['coeff_charges']
            ]);
            
            // Re-calculate the stored 'prix' (prix_vente) for all
            $references = Reference::all();
            foreach($references as $ref) {
                $ref->prix = $ref->prix_vente;
                $ref->save();
            }

            return back()->with('success', 'Paramètres mis à jour et appliqués à toutes les références.');
        }

        return back()->with('success', 'Paramètres par défaut mis à jour.');
    }


    public function create()
    {
        $pieces = Piece::all();
        $voitures = Voiture::all();
        $globalCoeff = Setting::where('key', 'global_coefficient')->value('value') ?? 0.20;
        $globalCoeffCharges = Setting::where('key', 'global_coeff_charges')->value('value') ?? 0.10;
        return view('admin.references.create', compact('pieces', 'voitures', 'globalCoeff', 'globalCoeffCharges'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:50|unique:references,reference',
            'nom' => 'required|string|max:100',
            'hs_code' => 'nullable|string|max:50',
            'origine' => 'nullable|string|max:50',
            'piece_id' => 'required|exists:pieces,id',
            'description' => 'nullable|string',
            'garantie' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'position' => 'nullable|integer',
            'prix_achat' => 'nullable|numeric|min:0',
            'coefficient_charges' => 'nullable|numeric|min:0',
            'coefficient_beneficiaire' => 'nullable|numeric|min:0',
            'prix' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'voiture_ids' => 'nullable|array',
            'voiture_ids.*' => 'exists:voitures,id'
        ]);

        $validated['slug'] = Str::slug($validated['nom']);
        $slug = $validated['slug'];
        $count = 1;
        while (Reference::where('slug', $slug)->exists()) {
            $slug = $validated['slug'] . '-' . $count++;
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('references', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        $reference = Reference::create($validated);

        if ($request->has('voiture_ids')) {
            $reference->voitures()->sync($request->voiture_ids);
        }

        return redirect()->route('admin.references.index')->with('success', 'reference cree.');
    }

    public function show(Reference $reference)
    {
        $reference->load(['piece', 'voitures']);
        return view('admin.references.show', compact('reference'));
    }

    public function edit(Reference $reference)
    {
        $pieces = Piece::all();
        $voitures = Voiture::all();
        return view('admin.references.edit', compact('reference', 'pieces', 'voitures'));
    }

    public function update(Request $request, Reference $reference)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:50|unique:references,reference,' . $reference->id,
            'nom' => 'required|string|max:100',
            'hs_code' => 'nullable|string|max:50',
            'origine' => 'nullable|string|max:50',
            'piece_id' => 'required|exists:pieces,id',
            'description' => 'nullable|string',
            'garantie' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'position' => 'nullable|integer',
            'prix_achat' => 'nullable|numeric|min:0',
            'coefficient_charges' => 'nullable|numeric|min:0',
            'coefficient_beneficiaire' => 'nullable|numeric|min:0',
            'prix' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'delete_images' => 'nullable|array',
            'voiture_ids' => 'nullable|array',
            'voiture_ids.*' => 'exists:voitures,id'
        ]);

        if ($validated['nom'] !== $reference->nom) {
            $slug = Str::slug($validated['nom']);
            $count = 1;
            while (Reference::where('slug', $slug)->where('id', '!=', $reference->id)->exists()) {
                $slug = $validated['slug'] . '-' . $count++;
            }
            $validated['slug'] = $slug;
        }

        $currentImages = $reference->images ?? [];

        //suppression de page
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imgToDelete) {
                Storage::disk('public')->delete($imgToDelete);
                $currentImages = array_filter($currentImages, fn($img) => $img !== $imgToDelete);
            }
        }

        //telechargement de page
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('references', 'public');
                $currentImages[] = $path;
            }
        }
        $validated['images'] = array_values($currentImages);

        $reference->update($validated);
// mise ajours de compatibilite si exist au mois une voiture 
        if ($request->has('voiture_ids')) {
            $reference->voitures()->sync($request->voiture_ids);
        } else {
            $reference->voitures()->detach();
        }

        return redirect()->route('admin.references.index')->with('success', 'reference mise a jour avec succes.');
    }

    public function destroy(Reference $reference)
    {
        if ($reference->images) {
            foreach ($reference->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $reference->delete();
        return redirect()->route('admin.references.index')->with('success', 'Référence supprimée avec succès.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
            'numero_facture' => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique('factures_fournisseur', 'numero')->where(function ($query) {
                    return $query->where('status', 'valide');
                })
            ],
            'fournisseur' => 'required|string',
            'date_facture' => 'required|date',
            'coeff_charges' => 'required|numeric|min:0',
            'coeff_benef' => 'required|numeric|min:0',
            'taux_conversion' => 'required|numeric|min:0',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Create the FactureFournisseur
            $facture = FactureFournisseur::create([
                'numero' => $request->numero_facture,
                'fournisseur' => $request->fournisseur,
                'date_facture' => $request->date_facture,
                'coefficient_charges' => $request->coeff_charges,
                'coefficient_beneficiaire' => $request->coeff_benef,
                'taux_conversion' => $request->taux_conversion,
                'fichier_original' => $request->file('file')->getClientOriginalName(),
            ]);

            // 2. Perform the Import
            $importer = new ReferenceImport($facture);
            Excel::import($importer, $request->file('file'));

            // 3. Update Facture with totals accumulated during import
            $facture->update([
                'total_achat_euro' => $importer->totalAchatEuro,
                'total_achat_mad' => $importer->totalAchatMad,
                'total_vente' => $importer->totalVente,
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.references.index')->with('success', 'Importation réussie. Facture #' . $facture->numero . ' enregistrée.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Import error: ' . $e->getMessage());
            return redirect()->route('admin.references.index')->with('error', 'Erreur lors de l\'importation : ' . $e->getMessage());
        }
    }
}

