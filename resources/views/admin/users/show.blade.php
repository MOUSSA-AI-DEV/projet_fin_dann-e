<x-app-layout>
    @section('page-title', 'Détails de l\'Utilisateur')

    <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); max-width: 800px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">Détails : {{ $user->nom }} {{ $user->prenom }}</h3>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('admin.users.index') }}" style="color: #64748b; text-decoration: none; font-size: 0.875rem;">← Retour</a>
                <a href="{{ route('admin.users.edit', $user) }}" style="padding: 0.5rem 1rem; background: #2563eb; color: white; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600;">Modifier</a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <h4 style="font-size: 0.85rem; color: #64748b; text-transform: uppercase; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">Informations Personnelles</h4>
                <div style="margin-bottom: 1rem;">
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Nom COMPLET</div>
                    <div style="font-size: 1rem; color: #1e293b;">{{ $user->nom }} {{ $user->prenom }}</div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">EMAIL</div>
                    <div style="font-size: 1rem; color: #1e293b;">{{ $user->email }}</div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">TÉLÉPHONE</div>
                    <div style="font-size: 1rem; color: #1e293b;">{{ $user->telephone ?? 'Non renseigné' }}</div>
                </div>
            </div>

            <div>
                <h4 style="font-size: 0.85rem; color: #64748b; text-transform: uppercase; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">Compte & Statut</h4>
                <div style="margin-bottom: 1rem;">
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">RÔLE</div>
                    <div>
                        <span style="background: #f1f5f9; padding: 2px 8px; border-radius: 99px; font-size: 0.8rem; color: #475569;">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">STATUT</div>
                    <div>
                        @if($user->is_active)
                            <span style="color: #166534; background: #dcfce7; padding: 4px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">ACTIF</span>
                        @else
                            <span style="color: #991b1b; background: #fee2e2; padding: 4px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">BANNI</span>
                        @endif
                    </div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">INSCRIT LE</div>
                    <div style="font-size: 1rem; color: #1e293b;">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <h4 style="font-size: 0.85rem; color: #64748b; text-transform: uppercase; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">Adresse de Livraison</h4>
            <div style="font-size: 1rem; color: #1e293b;">
                {{ $user->adresse_livraison ?? 'Non renseigné' }}<br>
                {{ $user->code_postal }} {{ $user->ville }}
            </div>
        </div>
    </div>
</x-app-layout>
