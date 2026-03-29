<x-app-layout>
    @section('page-title', 'Gestion des Pièces')

    <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
        <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1rem; align-items: center;">
                @if(session('success'))
                    <div style="background: #dcfce7; color: #166534; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem;">
                        {{ session('success') }}
                    </div>
                @endif
                <a href="{{ route('admin.pieces.create') }}" style="padding: 0.6rem 1.2rem; background: #2563eb; color: white; border-radius: 6px; text-decoration: none; font-size: 0.875rem; font-weight: 600;">+ Nouvelle Pièce</a>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">ID</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Nom</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Catégorie</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Marque</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Prix</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Statut</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pieces as $piece)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 1rem; font-weight: 600; color: #64748b;">#{{ $piece->id }}</td>
                            <td style="padding: 1rem;">
                                <div style="font-weight: 600; color: #1e293b;">{{ $piece->marque->nom }} - {{ $piece->nom }}</div>
                            </td>
                            <td style="padding: 1rem; color: #475569;">{{ $piece->category->nom }}</td>
                            <td style="padding: 1rem; color: #475569;">{{ $piece->marque->nom }}</td>
                            <td style="padding: 1rem; font-weight: 600;">{{ number_format($piece->prix, 2) }} €</td>
                            <td style="padding: 1rem;">
                                @if($piece->is_visible)
                                    <span style="color: #166534; background: #dcfce7; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Visible</span>
                                @else
                                    <span style="color: #991b1b; background: #fee2e2; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Masqué</span>
                                @endif
                            </td>
                            <td style="padding: 1rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="{{ route('admin.pieces.show', $piece) }}" style="padding: 5px 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; color: #475569; text-decoration: none; font-size: 0.8rem;">Détails</a>
                                <a href="{{ route('admin.pieces.edit', $piece) }}" style="padding: 5px 10px; background: #f1f5f9; border-radius: 4px; color: #475569; text-decoration: none; font-size: 0.8rem;">Modifier</a>
                                
                                <form action="{{ route('admin.pieces.destroy', $piece) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr ?');">
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
            {{ $pieces->links() }}
        </div>
    </div>
</x-app-layout>
