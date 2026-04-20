@extends('layouts.client')
@section('title', 'Mes Commandes')

@section('content')
<h1 class="section-title">Mes Commandes</h1>

@if($commandes->count() > 0)
    <div class="card" style="padding: 0; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); background: var(--bg-tertiary);">
                    <th style="padding: 1rem; text-align: left; border-radius: 12px 0 0 0;">Numéro</th>
                    <th style="padding: 1rem; text-align: left;">Date</th>
                    <th style="padding: 1rem; text-align: left;">Statut</th>
                    <th style="padding: 1rem; text-align: right;">Total</th>
                    <th style="padding: 1rem; text-align: center;">Facture</th>
                    <th style="padding: 1rem; text-align: right; border-radius: 0 12px 0 0;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commandes as $commande)
                <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.2s;">
                    <td style="padding: 1rem; font-family: monospace; font-weight: 600; color: white;">
                        <a href="{{ route('client.commande.detail', $commande->numero_commande) }}" style="text-decoration: underline; text-underline-offset: 4px; color: var(--accent);">{{ $commande->numero_commande }}</a>
                    </td>
                    <td style="padding: 1rem; color: var(--text-secondary);">
                        {{ $commande->created_at->format('d/m/Y à H:i') }}
                    </td>
                    <td style="padding: 1rem;">
                        @if($commande->statut === 'en_attente')
                            <span class="badge badge-gray">En attente</span>
                        @elseif($commande->statut === 'confirmee')
                            <span class="badge badge-blue">Confirmée</span>
                        @elseif($commande->statut === 'expediee')
                            <span class="badge badge-green">Expédiée</span>
                        @elseif($commande->statut === 'livree')
                            <span class="badge badge-green">Livrée</span>
                        @elseif($commande->statut === 'annulee')
                            <span class="badge badge-red">Annulée</span>
                        @else
                            <span class="badge badge-gray">{{ ucfirst($commande->statut) }}</span>
                        @endif
                    </td>
                    <td style="padding: 1rem; text-align: right; font-weight: 700;">
                        {{ number_format($commande->total, 2) }} €
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        @if($commande->facture_pdf)
                            <a href="{{ route('client.facture.download', $commande->id) }}" target="_blank" class="btn btn-sm btn-outline" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">📄 PDF</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <a href="{{ route('client.commande.detail', $commande->numero_commande) }}" class="btn btn-sm btn-outline">Détails</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="card" style="text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">📦</div>
        <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Aucune commande</h3>
        <p class="text-muted">Vous n'avez pas encore passé de commande.</p>
        <a href="{{ route('client.catalogue.index') }}" class="btn btn-primary" style="margin-top: 1.5rem;">Commencer mes achats</a>
    </div>
@endif
@endsection
