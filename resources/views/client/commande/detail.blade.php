@extends('layouts.client')
@section('title', 'Détails Commande ' . $commande->numero_commande)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 class="section-title" style="margin-bottom: 0;">Commande {{ $commande->numero_commande }}</h1>
    <a href="{{ route('client.commande.liste') }}" class="btn btn-outline">← Voir toutes mes commandes</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Ligne de commande -->
    <div class="card" style="padding: 0; overflow-x: auto;">
        <h3 style="padding: 1.5rem; border-bottom: 1px solid var(--border-color); margin: 0;">Articles Commandés</h3>
        <table style="width: 100%; border-collapse: collapse; min-width: 500px;">
            <thead>
                <tr style="background: var(--bg-tertiary);">
                    <th style="padding: 1rem; text-align: left;">Article</th>
                    <th style="padding: 1rem; text-align: left;">Photo</th>
                    <th style="padding: 1rem; text-align: center;">Prix unitaire</th>
                    <th style="padding: 1rem; text-align: center;">Quantité</th>
                    <th style="padding: 1rem; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commande->references as $ligne)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem;">
                        <div style="font-weight: 700;">{{ $ligne->reference->piece->nom ?? 'Pièce' }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-secondary);">Réf: <span style="font-family: monospace; color: white;">{{ $ligne->reference->reference }}</span></div>
                    </td>
                    <td style="padding: 10px;">
                        <div style="width: 50px; height: 50px; background: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--border-color); padding: 2px;">
                            @php 
                                $img = ($ligne->reference->images && count($ligne->reference->images) > 0) 
                                       ? $ligne->reference->images[0] 
                                       : ($ligne->reference->piece->images[0] ?? null);
                            @endphp
                            @if($img)
                                <img src="{{ asset('storage/'.$img) }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            @else
                                <span style="font-size: 1.2rem; filter: grayscale(1);">⚙️</span>
                            @endif
                        </div>
                    </td>
                    <td style="padding: 1rem; text-align: center;">{{ number_format($ligne->prix_unitaire, 2) }} €</td>
                    <td style="padding: 1rem; text-align: center;">x {{ $ligne->quantite }}</td>
                    <td style="padding: 1rem; text-align: right; font-weight: 700;">{{ number_format($ligne->total_ligne, 2) }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!--infos Commande -->
    <div>
        <div class="card" style="margin-bottom: 1.5rem;">
            <h3 style="margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-color);">Informations</h3>
            
            <div style="margin-bottom: 1rem;">
                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.2rem;">Date de commande</div>
                <div style="font-weight: 600;">{{ $commande->created_at->format('d/m/Y à H:i') }}</div>
            </div>
            
            <div style="margin-bottom: 1rem;">
                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.2rem;">Statut de livraison</div>
                <div style="font-weight: 600; text-transform: capitalize;">{{ str_replace('_', ' ', $commande->statut) }}</div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.2rem;">Notes client</div>
                <div style="font-style: italic;">{{ $commande->notes_client ?: 'Aucune note' }}</div>
            </div>

            <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; margin-top: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 700; font-size: 1.1rem;">Total</span>
                    <span style="font-weight: 800; font-size: 1.5rem; color: var(--accent);">{{ number_format($commande->total, 2) }} €</span>
                </div>
            </div>
        </div>

        @if($commande->facture_pdf)
        <a href="{{ route('client.facture.download', $commande->id) }}" target="_blank" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: 1.1rem;">
            📄 Télécharger la facture PDF
        </a>
        @endif
    </div>
</div>

<style>
    @media (max-width: 900px) {
        div[style*="grid-template-columns: 2fr 1fr"] { grid-template-columns: 1fr; }
    }
</style>
@endsection
