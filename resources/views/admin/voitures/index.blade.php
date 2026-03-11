<x-app-layout>
    @section('page-title', 'Gestion des Voitures')

    <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">Liste des Voitures</h3>
            <div style="display: flex; gap: 1rem; align-items: center;">
                @if(session('success'))
                    <div style="background: #dcfce7; color: #166534; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem;">
                        {{ session('success') }}
                    </div>
                @endif
                <a href="{{ route('admin.voitures.create') }}" style="padding: 0.6rem 1.2rem; background: #2563eb; color: white; border-radius: 6px; text-decoration: none; font-size: 0.875rem; font-weight: 600;">+ Ajouter une voiture</a>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Marque / Modèle</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Année</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Motorisation / Puissance</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Immatriculation</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($voitures as $voiture)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 1rem;">
                                <div style="font-weight: 500;">{{ $voiture->marque }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $voiture->modele }}</div>
                            </td>
                            <td style="padding: 1rem;">{{ $voiture->annee_debut }}</td>
                            <td style="padding: 1rem;">
                                <div>{{ $voiture->motorisation }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $voiture->puissance }} ch</div>
                            </td>
                            <td style="padding: 1rem;">{{ $voiture->immatriculation_type ?? '-' }}</td>
                            <td style="padding: 1rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="{{ route('admin.voitures.edit', $voiture) }}" style="padding: 5px 10px; background: #f1f5f9; border-radius: 4px; color: #475569; text-decoration: none; font-size: 0.8rem;">Modifier</a>
                                
                                <form action="{{ route('admin.voitures.destroy', $voiture) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr ?');">
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
            {{ $voitures->links() }}
        </div>
    </div>
</x-app-layout>
