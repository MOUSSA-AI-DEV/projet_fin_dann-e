<x-app-layout>
    @section('page-title', 'Détails Commande ' . $commande->numero_commande)

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; max-width: 1200px; margin: 0 auto;">
        
        <!-- Liste des articles -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0;">Articles Commandés</h3>
                    @if($commande->facture_pdf)
                        <a href="{{ route('admin.commandes.facture', $commande) }}" target="_blank" 
                           style="padding: 6px 12px; background: #1e293b; color: white; text-decoration: none; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                            📄 Voir la Facture
                        </a>
                    @endif
                </div>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #f1f5f9; background: #fafafa;">
                                <th style="padding: 0.75rem; color: #64748b; font-size: 0.7rem; text-transform: uppercase;">Photo</th>
                                <th style="padding: 0.75rem; color: #64748b; font-size: 0.7rem; text-transform: uppercase;">Référence</th>
                                <th style="padding: 0.75rem; color: #64748b; font-size: 0.7rem; text-transform: uppercase; text-align: center;">Prix Unitaire</th>
                                <th style="padding: 0.75rem; color: #64748b; font-size: 0.7rem; text-transform: uppercase; text-align: center;">Qté</th>
                                <th style="padding: 0.75rem; color: #64748b; font-size: 0.7rem; text-transform: uppercase; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commande->references as $ligne)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 0.75rem;">
                                        @php 
                                            $img = ($ligne->reference->images && count($ligne->reference->images) > 0) 
                                                   ? $ligne->reference->images[0] 
                                                   : ($ligne->reference->piece->images[0] ?? null);
                                        @endphp
                                        @if($img)
                                            <img src="{{ asset('storage/'.$img) }}" style="width: 40px; height: 40px; border-radius: 4px; object-fit: contain; border: 1px solid #eee;">
                                        @else
                                            <div style="width: 40px; height: 40px; border-radius: 4px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 1rem;">⚙️</div>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <div style="font-weight: 700; color: #1e293b; font-size: 0.875rem;">{{ $ligne->reference->nom }}</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">Code: {{ $ligne->reference->reference }}</div>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center; color: #475569;">{{ number_format($ligne->prix_unitaire, 2) }} €</td>
                                    <td style="padding: 0.75rem; text-align: center; color: #475569;">{{ $ligne->quantite }}</td>
                                    <td style="padding: 0.75rem; text-align: right; font-weight: 700; color: #1e293b;">{{ number_format($ligne->total_ligne, 2) }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="padding: 1rem; text-align: right; font-weight: 600; color: #64748b;">Total TTC</td>
                                <td style="padding: 1rem; text-align: right; font-weight: 800; color: #1e293b; font-size: 1.1rem;">{{ number_format($commande->total, 2, ',', ' ') }} €</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">Notes du Client</h3>
                <p style="color: #475569; font-style: italic;">{{ $commande->notes_client ?: 'Aucune note laissée par le client.' }}</p>
            </div>
        </div>

        <!-- Sidebar Actions & Client Info -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- gestion de status commande -->
            <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 1.5rem;">Gestion du Statut</h3>
                
                <form action="{{ route('admin.commandes.update', $commande) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Statut Livraison</label>
                        <select name="statut" style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; background: white;">
                            <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmee" {{ $commande->statut == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                            <option value="expediee" {{ $commande->statut == 'expediee' ? 'selected' : '' }}>Expédiée</option>
                            <option value="livree" {{ $commande->statut == 'livree' ? 'selected' : '' }}>Livrée</option>
                            <option value="annulee" {{ $commande->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">État du Paiement</label>
                        <select name="payment_status" style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.875rem; background: white;">
                            <option value="pending" {{ $commande->payment_status == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="paid" {{ $commande->payment_status == 'paid' ? 'selected' : '' }}>Réussi (Payé)</option>
                            <option value="failed" {{ $commande->payment_status == 'failed' ? 'selected' : '' }}>Échoué / Refusé</option>
                            <option value="refunded" {{ $commande->payment_status == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                        </select>
                    </div>

                    <button type="submit" style="width: 100%; padding: 0.75rem; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: 700; cursor: pointer;">
                        Mettre à jour
                    </button>
                </form>
            </div>

            <!-- Infos Client -->
            <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 1.25rem;">Informations Client</h3>
                
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #1e293b; font-size: 1.1rem;">
                        {{ strtoupper(substr($commande->user->prenom, 0, 1)) }}{{ strtoupper(substr($commande->user->nom, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 700; color: #1e293b;">{{ $commande->user->prenom }} {{ $commande->user->nom }}</div>
                        <div style="font-size: 0.8rem; color: #64748b;">{{ $commande->user->email }}</div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem;">
                    <div>
                        <div style="color: #64748b; font-weight: 600; margin-bottom: 0.2rem;">Téléphone</div>
                        <div style="color: #1e293b;">{{ $commande->user->telephone ?: 'Non renseigné' }}</div>
                    </div>
                    <div>
                        <div style="color: #64748b; font-weight: 600; margin-bottom: 0.2rem;">Adresse de livraison</div>
                        <div style="color: #1e293b; line-height: 1.4;">
                            {{ $commande->user->adresse_livraison }}<br>
                            {{ $commande->user->code_postal }} {{ $commande->user->ville }}
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.commandes.index') }}" style="text-align: center; color: #64748b; font-size: 0.875rem; text-decoration: none; font-weight: 600;">← Retour à la liste</a>
        </div>

    </div>
</x-app-layout>
