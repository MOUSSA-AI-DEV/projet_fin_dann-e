<x-app-layout>
    @section('page-title', 'Gestion des Utilisateurs')

    <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">Liste des Utilisateurs</h3>
            @if(session('success'))
                <div style="background: #dcfce7; color: #166534; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background: #fee2e2; color: #991b1b; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem;">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">ID</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Nom / Prénom</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Email</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Rôle</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Statut</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.8rem; text-transform: uppercase; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 1rem; font-weight: 600; color: #64748b;">#{{ $user->id }}</td>
                            <td style="padding: 1rem; font-weight: 500;">{{ $user->nom }} {{ $user->prenom }}</td>
                            <td style="padding: 1rem; color: #475569;">{{ $user->email }}</td>
                            <td style="padding: 1rem;">
                                <span style="background: #f1f5f9; padding: 2px 8px; border-radius: 99px; font-size: 0.75rem; color: #475569;">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td style="padding: 1rem;">
                                @if($user->is_active)
                                    <span style="color: #166534; background: #dcfce7; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Actif</span>
                                @else
                                    <span style="color: #991b1b; background: #fee2e2; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Banni</span>
                                @endif
                            </td>
                            <td style="padding: 1rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="{{ route('admin.users.edit', $user) }}" style="padding: 5px 10px; background: #f1f5f9; border-radius: 4px; color: #475569; text-decoration: none; font-size: 0.8rem;">Modifier</a>
                                
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" style="padding: 5px 10px; border: none; border-radius: 4px; color: white; background: {{ $user->is_active ? '#ef4444' : '#10b981' }}; font-size: 0.8rem; cursor: pointer;">
                                        {{ $user->is_active ? 'Bannir' : 'Débannir' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
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
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
