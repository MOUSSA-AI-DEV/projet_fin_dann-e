<x-app-layout>
    @section('page-title', 'Factures Fournisseur')

    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">Factures Fournisseur</h3>
                <p style="font-size: 0.875rem; color: #64748b;">Historique des importations et factures enregistrées.</p>
            </div>
        </div>

        @if($factures->isEmpty())
            <div style="text-align: center; padding: 4rem 2rem; color: #94a3b8;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📑</div>
                <p style="font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Aucune facture enregistrée</p>
                <p style="font-size: 0.875rem;">Importez un fichier Excel pour générer une facture automatiquement.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #f1f5f9; background: #fafafa;">
                            <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">N° Facture</th>
                            <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Fournisseur</th>
                            <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Date</th>
                            <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Statut</th>
                            <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; text-align: center;">Articles</th>
                            <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Total (€)</th>
                            <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Total (MAD)</th>
                            <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Total Vente</th>
                            <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Marge</th>
                            <th style="padding: 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($factures as $facture)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 1rem; font-weight: 700; color: #1e293b;">
                                    {{ $facture->numero }}
                                </td>
                                <td style="padding: 1rem; color: #475569;">{{ $facture->fournisseur }}</td>
                                <td style="padding: 1rem; color: #64748b; font-size: 0.875rem;">{{ $facture->date_facture->format('d/m/Y') }}</td>
                                <td style="padding: 1rem;">
                                    @if($facture->status === 'valide')
                                        <span style="padding: 0.2rem 0.6rem; background: #dcfce7; color: #166534; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Valide</span>
                                    @else
                                        <span style="padding: 0.2rem 0.6rem; background: #fee2e2; color: #991b1b; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Annulée</span>
                                    @endif
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <span style="display: inline-block; padding: 0.2rem 0.6rem; background: #eff6ff; color: #2563eb; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
                                        {{ $facture->references_count }}
                                    </span>
                                </td>
                                <td style="padding: 1rem; font-weight: 600; color: #475569;">{{ number_format($facture->total_achat_euro, 2, ',', ' ') }} €</td>
                                <td style="padding: 1rem; font-weight: 600; color: #1e293b;">{{ number_format($facture->total_achat_mad, 2, ',', ' ') }} MAD</td>
                                <td style="padding: 1rem; font-weight: 700; color: #2563eb;">{{ number_format($facture->total_vente, 2, ',', ' ') }} MAD</td>
                                <td style="padding: 1rem; font-weight: 700; color: #059669;">+{{ number_format($facture->marge_totale, 2, ',', ' ') }} MAD</td>
                                <td style="padding: 1rem; text-align: right;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <a href="{{ route('admin.factures-fournisseur.show', $facture) }}" style="padding: 0.4rem 0.8rem; background: #f1f5f9; color: #475569; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">Détails / Collection</a>
                                        @if($facture->status === 'valide')
                                            <form action="{{ route('admin.factures-fournisseur.destroy', $facture) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment annuler et archiver cette facture ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" style="padding: 0.4rem 0.8rem; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: 600;">Annuler</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 1.5rem;">
                {{ $factures->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
