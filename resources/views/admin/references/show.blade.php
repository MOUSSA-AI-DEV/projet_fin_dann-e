<x-app-layout>
    @section('page-title', 'Détails de la Référence')

    <div style="max-width: 900px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">{{ $reference->nom }}</h3>
                <span style="font-size: 0.875rem; color: #64748b; background: #f1f5f9; padding: 4px 12px; border-radius: 20px;">ID #{{ $reference->id }}</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.5rem;">Code Référence</label>
                    <div style="font-size: 1.1rem; color: #1e293b; font-weight: 600;">{{ $reference->reference }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.5rem;">Garantie</label>
                    <div style="font-size: 1.1rem; color: #1e293b;">{{ $reference->garantie ?? 'Non spécifiée' }}</div>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.5rem;">Description</label>
                <div style="color: #475569; line-height: 1.6;">{{ $reference->description ?: 'Aucune description disponible.' }}</div>
            </div>

            @if($reference->images && count($reference->images) > 0)
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 1rem;">Images Galerie</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                        @foreach($reference->images as $image)
                            <div style="aspect-ratio: 1; border-radius: 8px; border: 1px solid #f1f5f9; overflow: hidden; background: white; cursor: pointer;">
                                <img src="{{ asset('storage/' . $image) }}" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 1rem;">Voitures Compatibles ({{ $reference->voitures->count() }})</label>
                @if($reference->voitures->count() > 0)
                    <div style="border: 1px solid #f1f5f9; border-radius: 8px; overflow: hidden;">
                        @foreach($reference->voitures as $voiture)
                            <div style="padding: 1rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; {{ $loop->last ? 'border-bottom: none' : '' }}">
                                <div>
                                    <div style="font-weight: 600; color: #1e293b;">{{ $voiture->marque }} {{ $voiture->modele }}</div>
                                    <div style="font-size: 0.8rem; color: #64748b;">{{ $voiture->annee_debut }} - {{ $voiture->motorisation }} ({{ $voiture->puissance }} ch)</div>
                                </div>
                                <span style="font-size: 0.75rem; color: #94a3b8; background: #f8fafc; padding: 2px 8px; border-radius: 4px;">#{{ $voiture->id }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding: 2rem; background: #f8fafc; border-radius: 8px; text-align: center; color: #64748b; border: 1px dashed #e2e8f0;">
                        Aucune voiture compatible associée.
                    </div>
                @endif
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                <h4 style="font-size: 0.9rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">Pièce Associée</h4>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">📦</div>
                    <div>
                        <div style="font-weight: 600; color: #1e293b;">{{ $reference->piece->nom }}</div>
                        <div style="font-size: 0.8rem; color: #64748b;">OEM: {{ $reference->piece->reference_oem }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.pieces.edit', $reference->piece) }}" style="display: block; text-align: center; padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 6px; text-decoration: none; color: #475569; font-size: 0.875rem; font-weight: 600;">Voir la pièce</a>
            </div>

            <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                <h4 style="font-size: 0.9rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">Statut & Actions</h4>
                <div style="margin-bottom: 1.5rem;">
                    @if($reference->is_active)
                        <span style="display: block; text-align: center; color: #166534; background: #dcfce7; padding: 6px; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">Référence Active</span>
                    @else
                        <span style="display: block; text-align: center; color: #991b1b; background: #fee2e2; padding: 6px; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">Référence Inactive</span>
                    @endif
                </div>
                <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem;">
                    <a href="{{ route('admin.references.edit', $reference) }}" style="display: block; text-align: center; padding: 0.75rem; background: #2563eb; border-radius: 6px; text-decoration: none; color: white; font-size: 0.875rem; font-weight: 600;">Modifier</a>
                    <a href="{{ route('admin.references.index') }}" style="display: block; text-align: center; padding: 0.75rem; background: #f1f5f9; border-radius: 6px; text-decoration: none; color: #475569; font-size: 0.875rem; font-weight: 600;">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
