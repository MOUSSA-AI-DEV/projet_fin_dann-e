<x-app-layout>
    @section('page-title', 'Détails Facture #' . $facture->numero)

    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.factures-fournisseur.index') }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 600;">← Retour à la liste</a>
    </div>

    <!-- Header Stats -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Total Achat (€)</p>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">{{ number_format($facture->total_achat_euro, 2, ',', ' ') }} €</h3>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Total Achat (MAD)</p>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #475569;">{{ number_format($facture->total_achat_mad, 2, ',', ' ') }} MAD</h3>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Total Vente (MAD)</p>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #2563eb;">{{ number_format($facture->total_vente, 2, ',', ' ') }} MAD</h3>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Marge Estimée</p>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #059669;">+{{ number_format($facture->marge_totale, 2, ',', ' ') }} MAD</h3>
        </div>
    </div>

    <!-- Invoice Details & Summary -->
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); height: fit-content;">
            <h4 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 1.5rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.75rem;">Infos Facture</h4>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <span style="display: block; font-size: 0.75rem; font-weight: 600; color: #94a3b8; text-transform: uppercase;">N° Facture</span>
                    <span style="font-weight: 700; color: #1e293b;">{{ $facture->numero }}</span>
                </div>
                <div>
                    <span style="display: block; font-size: 0.75rem; font-weight: 600; color: #94a3b8; text-transform: uppercase;">Fournisseur</span>
                    <span style="font-weight: 600; color: #475569;">{{ $facture->fournisseur }}</span>
                </div>
                <div>
                    <span style="display: block; font-size: 0.75rem; font-weight: 600; color: #94a3b8; text-transform: uppercase;">Date</span>
                    <span style="color: #475569;">{{ $facture->date_facture->format('d/m/Y') }}</span>
                </div>
                <div style="margin-top: 0.5rem; padding-top: 1rem; border-top: 1px dashed #e2e8f0;">
                    <span style="display: block; font-size: 0.75rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.5rem;">Paramètres d'import</span>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                            <span style="color: #64748b;">Taux (1€)</span>
                            <span style="font-weight: 600; color: #1e293b;">{{ number_format($facture->taux_conversion, 2) }} MAD</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                            <span style="color: #64748b;">Coeff. Charges</span>
                            <span style="font-weight: 600; color: #1e293b;">{{ $facture->coefficient_charges * 100 }}%</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                            <span style="color: #64748b;">Coeff. Bénéf.</span>
                            <span style="font-weight: 600; color: #1e293b;">{{ $facture->coefficient_beneficiaire * 100 }}%</span>
                        </div>
                    </div>
                </div>
                @if($facture->notes)
                    <div style="margin-top: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <span style="display: block; font-size: 0.75rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.5rem;">Notes</span>
                        <p style="font-size: 0.875rem; color: #475569; margin: 0;">{{ $facture->notes }}</p>
                    </div>
                @endif
                @if($facture->fichier_original)
                    <div style="margin-top: 1rem;">
                         <span style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: #64748b;">
                            <span>📄</span> Fichier: {{ $facture->fichier_original }}
                         </span>
                    </div>
                @endif
            </div>
        </div>

        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h4 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 1.5rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.75rem;">Collection des Références Importées</h4>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #f1f5f9;">
                            <th style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.7rem; text-transform: uppercase;">Référence</th>
                            <th style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.7rem; text-transform: uppercase;">Désignation</th>
                            <th style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.7rem; text-transform: uppercase;">HS Code / Origine</th>
                            <th style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.7rem; text-transform: uppercase; text-align: center;">Stock</th>
                            <th style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.7rem; text-transform: uppercase;">P. Achat (MAD)</th>
                            <th style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.7rem; text-transform: uppercase;">P. Vente (MAD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facture->references as $ref)
                            <tr style="border-bottom: 1px solid #f8fafc;">
                                <td style="padding: 0.75rem 1rem; font-weight: 700; font-size: 0.85rem; color: #1e293b;">{{ $ref->reference }}</td>
                                <td style="padding: 0.75rem 1rem; font-size: 0.85rem;">
                                    <div style="font-weight: 600; color: #475569;">{{ $ref->nom }}</div>
                                    <div style="font-size: 0.75rem; color: #94a3b8;">{{ $ref->piece->nom ?? '—' }}</div>
                                </td>
                                <td style="padding: 0.75rem 1rem;">
                                    <div style="font-weight: 600; color: #475569; font-size: 0.8rem;">{{ $ref->hs_code ?? '—' }}</div>
                                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">{{ $ref->origine ?? '—' }}</div>
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: center;">
                                    <span style="font-weight: 700; color: #475569;">{{ $ref->stock }}</span>
                                </td>
                                <td style="padding: 0.75rem 1rem; font-size: 0.85rem; color: #64748b;">{{ number_format($ref->prix_achat, 2, ',', ' ') }}</td>
                                <td style="padding: 0.75rem 1rem; font-weight: 700; font-size: 0.85rem; color: #2563eb;">{{ number_format($ref->prix_vente, 2, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
