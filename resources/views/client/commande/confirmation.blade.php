@extends('layouts.client')
@section('title', 'Confirmation de Commande')

@section('content')
<div style="max-width: 600px; margin: 0 auto; text-align: center; padding: 3rem 1rem;">
    <div style="font-size: 6rem; color: var(--success); margin-bottom: 1rem; line-height: 1;">✅</div>
    
    <h1 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 0.5rem;">Commande Validée !</h1>
    <h2 style="font-size: 1.5rem; color: var(--text-secondary); margin-bottom: 2rem;">Numéro : <span style="color: white; font-family: monospace;">{{ $commande->numero_commande }}</span></h2>
    
    <div class="card" style="margin-bottom: 2rem; border: 1px solid var(--success); background: rgba(16, 185, 129, 0.05);">
        <p style="font-size: 1.1rem; margin-bottom: 0.5rem;">Total de votre commande : <strong style="font-size: 1.3rem;">{{ number_format($commande->total, 2) }} €</strong></p>
        <p style="color: var(--text-secondary); font-size: 0.95rem;">Un technicien va préparer votre commande dans les plus brefs délais.</p>
    </div>

    @if($commande->facture_pdf)
    <a href="{{ route('client.facture.download', $commande->id) }}" class="btn btn-primary" target="_blank" style="padding: 1.2rem 2.5rem; font-size: 1.2rem; display: inline-flex; flex-direction: column; gap: 0.2rem; margin-bottom: 2rem; width: 100%;">
        <span style="font-size: 1.5rem;">🖨️ TÉLÉCHARGER LA FACTURE (PDF)</span>
        <span style="font-size: 0.8rem; font-weight: 400; opacity: 0.8;">Ouvrez le fichier puis faites Ctrl+P pour l'imprimer</span>
    </a>
    @endif

    <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="{{ route('client.accueil') }}" class="btn btn-outline">Retour à l'accueil</a>
        <a href="{{ route('client.commande.detail', $commande->numero_commande) }}" class="btn btn-outline" style="border-color: var(--accent); color: var(--accent);">Détails de la commande</a>
    </div>
</div>
@endsection
