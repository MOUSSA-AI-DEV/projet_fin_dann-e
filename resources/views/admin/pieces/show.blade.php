<x-app-layout>
    @section('page-title', 'Détails de la Pièce')

    <div style="max-width: 1000px; margin: 0 auto;">
        <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
                <div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">{{ $piece->nom }}</h3>
                    <div style="display: flex; gap: 0.5rem;">
                        <span style="font-size: 0.75rem; color: #64748b; background: #f1f5f9; padding: 2px 10px; border-radius: 20px;">{{ $piece->category->nom }}</span>
                        <span style="font-size: 0.75rem; color: #64748b; background: #f1f5f9; padding: 2px 10px; border-radius: 20px;">{{ $piece->marque->nom }}</span>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: #2563eb;">{{ number_format($piece->prix, 2) }} €</div>
                    <div style="font-size: 0.875rem; color: {{ $piece->stock > 5 ? '#166534' : '#991b1b' }}; font-weight: 600;">Stock: {{ $piece->stock }}</div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; padding: 1.5rem; background: #f8fafc; border-radius: 8px; margin-bottom: 2rem;">
                <div>
                    <div style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Référence OEM</div>
                    <div style="font-weight: 600; color: #1e293b;">{{ $piece->reference_oem }}</div>
                </div>
                <div>
                    <div style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Réf. Fournisseur</div>
                    <div style="font-weight: 600; color: #1e293b;">{{ $piece->reference_fournisseur ?: '-' }}</div>
                </div>
                <div>
                    <div style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Statut</div>
                    @if($piece->is_visible)
                        <span style="color: #166534; font-weight: 600; font-size: 0.875rem;">● En ligne</span>
                    @else
                        <span style="color: #94a3b8; font-weight: 600; font-size: 0.875rem;">○ Hors ligne</span>
                    @endif
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 0.9rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem; text-transform: uppercase;">Description</h4>
                <div style="color: #475569; line-height: 1.6;">{{ $piece->description ?: 'Aucune description.' }}</div>
            </div>
        </div>

        <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h4 style="font-size: 1rem; font-weight: 700; color: #1e293b;">RÉFÉRENCES & COMPATIBILITÉ ({{ $piece->references->count() }})</h4>
                <a href="{{ route('admin.references.create', ['piece_id' => $piece->id]) }}" style="font-size: 0.8rem; color: #2563eb; font-weight: 600; text-decoration: none;">+ Ajouter une référence</a>
            </div>

            @if($piece->references->count() > 0)
                <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                    @foreach($piece->references as $reference)
                        <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.75rem;">
                                <div>
                                    <span style="font-weight: 700; color: #1e293b; font-size: 1.1rem;">{{ $reference->reference }}</span>
                                    <span style="font-size: 0.85rem; color: #64748b; margin-left: 0.5rem;">- {{ $reference->nom }}</span>
                                </div>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('admin.references.show', $reference) }}" style="padding: 4px 10px; font-size: 0.75rem; color: #475569; background: #f1f5f9; border-radius: 4px; text-decoration: none;">Voir</a>
                                    <a href="{{ route('admin.references.edit', $reference) }}" style="padding: 4px 10px; font-size: 0.75rem; color: #475569; background: #f1f5f9; border-radius: 4px; text-decoration: none;">Modifier</a>
                                </div>
                            </div>
                            
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.5rem;">Voitures Compatibles</div>
                                @if($reference->voitures->count() > 0)
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                        @foreach($reference->voitures as $voiture)
                                            <span style="font-size: 0.75rem; background: #eff6ff; color: #2563eb; padding: 4px 12px; border-radius: 20px; font-weight: 600;">
                                                {{ $voiture->marque }} {{ $voiture->modele }} ({{ $voiture->annee_debut }})
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Aucune voiture compatible associée à cette référence.</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="padding: 3rem; background: #f8fafc; border: 1px dashed #e2e8f0; border-radius: 8px; text-align: center;">
                    <div style="font-size: 2rem; margin-bottom: 1rem;">📑</div>
                    <div style="color: #64748b; font-weight: 500;">Aucune référence enregistrée pour cette pièce.</div>
                    <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 0.5rem;">Les références permettent de lier une pièce à des voitures spécifiques.</div>
                </div>
            @endif
        </div>
        
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <a href="{{ route('admin.pieces.index') }}" style="padding: 0.75rem 1.5rem; background: #f1f5f9; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.9rem; font-weight: 600;">← Revenir à la liste</a>
            <a href="{{ route('admin.pieces.edit', $piece) }}" style="padding: 0.75rem 1.5rem; background: #1e293b; color: white; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600;">Modifier la pièce</a>
        </div>
    </div>
</x-app-layout>
