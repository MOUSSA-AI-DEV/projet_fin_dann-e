<x-app-layout>
    @section('page-title', 'Gestion des Commandes')

    <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
        <a href="{{ route('admin.commandes.create') }}" style="padding: 0.75rem 1.5rem; background: #2563eb; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
            + Créer une commande
        </a>
    </div>

    <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
        
        <!-- Filtres -->
        <div style="margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
            <form action="{{ route('admin.commandes.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; flex: 1;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Rechercher (N°, Client)</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ex: CMD-..." 
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem;">
                </div>
                
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Statut Commande</label>
                    <select name="statut" style="padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; background: white;">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmee" {{ request('statut') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                        <option value="expediee" {{ request('statut') == 'expediee' ? 'selected' : '' }}>Expédiée</option>
                        <option value="livree" {{ request('statut') == 'livree' ? 'selected' : '' }}>Livrée</option>
                        <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Paiement</label>
                    <select name="payment_status" style="padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; background: white;">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Réussi</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" style="padding: 0.65rem 1.25rem; background: #1e293b; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Filtrer</button>
                    <a href="{{ route('admin.commandes.index') }}" style="padding: 0.65rem 1.25rem; background: #f1f5f9; color: #475569; text-decoration: none; border-radius: 6px; font-weight: 600;">Réinitialiser</a>
                </div>
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #f1f5f9; background: #fafafa;">
                        <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">N° Commande</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Client</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Date</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Statut</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Paiement</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Total</th>
                        <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $commande)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;">
                            <td style="padding: 1rem; font-weight: 700; color: #1e293b; font-size: 0.875rem;">{{ $commande->numero_commande }}</td>
                            <td style="padding: 1rem;">
                                <div style="font-weight: 600; color: #1e293b; font-size: 0.875rem;">{{ $commande->user->prenom }} {{ $commande->user->nom }}</div>
                                <div style="font-size: 0.78rem; color: #64748b;">{{ $commande->user->email }}</div>
                            </td>
                            <td style="padding: 1rem; color: #475569; font-size: 0.875rem;">{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                            <td style="padding: 1rem;">
                                @php
                                    $statusPill = [
                                        'en_attente' => ['background' => '#fef3c7', 'color' => '#92400e', 'label' => 'En attente'],
                                        'confirmee' => ['background' => '#dcfce7', 'color' => '#166534', 'label' => 'Confirmée'],
                                        'expediee' => ['background' => '#ede9fe', 'color' => '#5b21b6', 'label' => 'Expédiée'],
                                        'livree' => ['background' => '#dcfce7', 'color' => '#166534', 'label' => 'Livrée'],
                                        'annulee' => ['background' => '#fee2e2', 'color' => '#991b1b', 'label' => 'Annulée'],
                                    ];
                                    $pill = $statusPill[$commande->statut] ?? ['background' => '#f1f5f9', 'color' => '#475569', 'label' => $commande->statut];
                                @endphp
                                <span style="display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: {{ $pill['background'] }}; color: {{ $pill['color'] }};">
                                    {{ $pill['label'] }}
                                </span>
                            </td>
                            <td style="padding: 1rem;">
                                @if($commande->payment_status === 'paid')
                                    <span style="color: #059669; font-size: 0.875rem; font-weight: 600;">● Payé</span>
                                @elseif($commande->payment_status === 'pending')
                                    <span style="color: #d97706; font-size: 0.875rem; font-weight: 600;">● En attente</span>
                                @else
                                    <span style="color: #dc2626; font-size: 0.875rem; font-weight: 600;">● Échoué</span>
                                @endif
                            </td>
                            <td style="padding: 1rem; font-weight: 700; color: #1e293b; font-size: 0.875rem;">{{ number_format($commande->total, 2, ',', ' ') }} €</td>
                            <td style="padding: 1rem; text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <a href="{{ route('admin.commandes.show', $commande) }}" style="padding: 6px 12px; background: #eff6ff; border-radius: 6px; color: #2563eb; text-decoration: none; font-size: 0.78rem; font-weight: 600;">Détails</a>
                                    @if($commande->facture_pdf)
                                        <a href="{{ route('admin.commandes.facture', $commande) }}" target="_blank" style="padding: 6px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.78rem; font-weight: 600;">Facture</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 3rem; text-align: center; color: #94a3b8;">
                                <div style="font-size: 2.5rem; margin-bottom: 1rem;">📦</div>
                                <p>Aucune commande trouvée.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $commandes->links() }}
        </div>
    </div>
</x-app-layout>
