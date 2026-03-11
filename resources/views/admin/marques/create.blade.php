<x-app-layout>
    @section('page-title', 'Ajouter une Marque')

    <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); max-width: 600px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">Nouvelle Marque</h3>
            <a href="{{ route('admin.marques.index') }}" style="color: #64748b; text-decoration: none; font-size: 0.875rem;">← Retour</a>
        </div>

        <form action="{{ route('admin.marques.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Nom de la marque</label>
                <input type="text" name="nom" value="{{ old('nom') }}" required
                       style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                @error('nom') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">URL du Logo</label>
                <input type="url" name="logo_url" value="{{ old('logo_url') }}"
                       style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                @error('logo_url') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                <label for="is_active" style="font-size: 0.85rem; color: #475569;">Marque active</label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('admin.marques.index') }}" style="padding: 0.75rem 1.5rem; background: #f1f5f9; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.9rem; font-weight: 600;">Annuler</a>
                <button type="submit" style="padding: 0.75rem 1.5rem; background: #2563eb; color: white; border: none; border-radius: 6px; font-size: 0.9rem; font-weight: 600; cursor: pointer;">Enregistrer</button>
            </div>
        </form>
    </div>
</x-app-layout>
