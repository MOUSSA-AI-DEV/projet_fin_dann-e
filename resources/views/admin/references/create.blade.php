<x-app-layout>
    @section('page-title', 'Ajouter une Référence')

    <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); max-width: 850px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">Nouvelle Référence</h3>
            <a href="{{ route('admin.references.index') }}" style="color: #64748b; text-decoration: none; font-size: 0.875rem;">← Retour</a>
        </div>

        <form action="{{ route('admin.references.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Code Référence</label>
                    <input type="text" name="reference" value="{{ old('reference') }}" required
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Nom de référence</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Pièce Associée</label>
                    <select name="piece_id" required style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem; background: white;">
                        <option value="">-- Sélectionner --</option>
                        @foreach($pieces as $piece)
                            <option value="{{ $piece->id }}" {{ old('piece_id') == $piece->id ? 'selected' : '' }}>{{ $piece->nom }} ({{ $piece->reference_oem }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Garantie</label>
                    <input type="text" name="garantie" value="{{ old('garantie') }}" placeholder="Ex: 2 ans"
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Prix (€)</label>
                    <input type="number" step="0.01" name="prix" value="{{ old('prix') }}" placeholder="0.00"
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Stock Initial</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}"
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Description</label>
                <textarea name="description" rows="3" 
                          style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">{{ old('description') }}</textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Images du produit</label>
                <input type="file" name="images[]" multiple accept="image/*"
                       style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem; background: white;">
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.3rem;">Vous pouvez sélectionner plusieurs images.</p>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Voitures Compatibles</label>
                <div style="max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.75rem;">
                    @foreach($voitures as $voiture)
                        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.4rem;">
                            <input type="checkbox" name="voiture_ids[]" value="{{ $voiture->id }}" id="voiture_{{ $voiture->id }}"
                                   {{ is_array(old('voiture_ids')) && in_array($voiture->id, old('voiture_ids')) ? 'checked' : '' }}>
                            <label for="voiture_{{ $voiture->id }}" style="font-size: 0.85rem; color: #475569;">
                                {{ $voiture->marque }} {{ $voiture->modele }} ({{ $voiture->annee_debut }}) - {{ $voiture->motorisation }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Position</label>
                    <input type="number" name="position" value="{{ old('position', '0') }}"
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                </div>
                <div style="display: flex; align-items: flex-end; padding-bottom: 0.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label for="is_active" style="font-size: 0.85rem; color: #475569;">Référence active</label>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('admin.references.index') }}" style="padding: 0.75rem 1.5rem; background: #f1f5f9; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.9rem; font-weight: 600;">Annuler</a>
                <button type="submit" style="padding: 0.75rem 1.5rem; background: #2563eb; color: white; border: none; border-radius: 6px; font-size: 0.9rem; font-weight: 600; cursor: pointer;">Enregistrer</button>
            </div>
        </form>
    </div>
</x-app-layout>
