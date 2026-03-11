<x-app-layout>
    @section('page-title', 'Gestion des Références')

    <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">Liste des Références</h3>
            <div style="display: flex; gap: 1rem; align-items: center;">
                @if(session('success'))
                    <div style="background: #dcfce7; color: #166534; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem;">
                        {{ session('success') }}
                    </div>
                @endif
                <a href="{{ route('admin.references.create') }}" style="padding: 0.6rem 1.2rem; background: #2563eb; color: white; border-radius: 6px; text-decoration: none; font-size: 0.875rem; font-weight: 600;">+ Nouvelle Référence</a>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">ID</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Code / Nom</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Piéce Associée</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Voitures Compatibles</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Garantie</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Statut</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($references as $reference)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 1rem; font-weight: 600; color: #64748b;">#{{ $reference->id }}</td>
                            <td style="padding: 1rem;">
                                <div style="font-weight: 500;">{{ $reference->reference }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $reference->nom }}</div>
                            </td>
                            <td style="padding: 1rem; color: #475569;">{{ $reference->piece->nom }}</td>
                            <td style="padding: 1rem;">
                                @if($reference->voitures->count() > 0)
                                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; background: #eff6ff; color: #2563eb; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                        {{ $reference->voitures->count() }} voiture(s)
                                    </span>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.75rem;">Aucune</span>
                                @endif
                            </td>
                            <td style="padding: 1rem;">{{ $reference->garantie ?? '-' }}</td>
                            <td style="padding: 1rem;">
                                @if($reference->is_active)
                                    <span style="color: #166534; background: #dcfce7; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Actif</span>
                                @else
                                    <span style="color: #991b1b; background: #fee2e2; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Inactif</span>
                                @endif
                            </td>
                            <td style="padding: 1rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="{{ route('admin.references.show', $reference) }}" style="padding: 5px 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; color: #475569; text-decoration: none; font-size: 0.8rem;">Détails</a>
                                <a href="{{ route('admin.references.edit', $reference) }}" style="padding: 5px 10px; background: #f1f5f9; border-radius: 4px; color: #475569; text-decoration: none; font-size: 0.8rem;">Modifier</a>
                                
                                <form action="{{ route('admin.references.destroy', $reference) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 5px 10px; border: none; border-radius: 4px; color: white; background: #1e293b; font-size: 0.8rem; cursor: pointer;">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $references->links() }}
        </div>
    </div>
</x-app-layout>
