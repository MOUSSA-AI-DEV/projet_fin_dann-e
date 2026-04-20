@extends('layouts.client')
@section('title', 'Mon Panier')

@section('content')
<h1 class="section-title">Mon Panier</h1>

@if(empty($panier))
    <div class="card" style="text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 5rem; margin-bottom: 1rem;">🛒</div>
        <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">Votre panier est vide</h3>
        <p class="text-muted" style="margin-bottom: 2rem;">Vous n'avez pas encore ajouté de pièces à votre panier.</p>
        <a href="{{ route('client.catalogue.index') }}" class="btn btn-primary">Continuer mes achats</a>
    </div>
@else
    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem;">
        <div>
            <div class="card" style="padding: 0; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color); background: var(--bg-tertiary);">
                            <th style="padding: 1rem; text-align: left; border-radius: 12px 0 0 0;">Article</th>
                            <th style="padding: 1rem; text-align: center;">Prix unitaire</th>
                            <th style="padding: 1rem; text-align: center;">Quantité</th>
                            <th style="padding: 1rem; text-align: right;">Total</th>
                            <th style="padding: 1rem; text-align: center; border-radius: 0 12px 0 0;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($panier as $id => $item)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1rem;">
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    <div style="width: 60px; height: 60px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        @if(isset($item['image_url']) && $item['image_url'])
                                            <img src="{{ asset('storage/'.$item['image_url']) }}" style="max-width: 100%; max-height: 100%; padding: 0.2rem;">
                                        @else
                                            <span style="color:#ccc;">⚙️</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight: 700;">{{ $item['piece_nom'] ?? 'Pièce' }}</div>
                                        <div style="font-size: 0.85rem; color: var(--text-secondary);">Réf: <span style="font-family: monospace; color: white;">{{ $item['code'] }}</span></div>
                                        <div style="font-size: 0.85rem; color: var(--text-secondary);">{{ $item['nom'] ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 1rem; text-align: center; font-weight: 600;">{{ number_format($item['prix'], 2) }} €</td>
                            <td style="padding: 1rem; text-align: center;">
                                <form action="{{ route('client.panier.modifier') }}" method="POST" style="display: flex; justify-content: center; gap: 0.5rem; align-items: center;">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="reference_id" value="{{ $id }}">
                                    <input type="number" name="quantite" value="{{ $item['quantite'] }}" min="1" class="form-control" style="width: 70px; padding: 0.4rem; text-align: center;" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td style="padding: 1rem; text-align: right; font-weight: 800; color: var(--accent);">
                                {{ number_format($item['prix'] * $item['quantite'], 2) }} €
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <form action="{{ route('client.panier.supprimer', $id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #f87171; cursor: pointer; font-size: 1.2rem; padding: 0.5rem; border-radius: 4px;" title="Supprimer">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 1rem;">
                <a href="{{ route('client.catalogue.index') }}" class="btn btn-outline">← Continuer mes achats</a>
            </div>
        </div>
        
        <div>
            <div class="card" style="position: sticky; top: 100px;">
                <h3 style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">Résumé</h3>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--text-secondary);">
                    <span>Sous-total</span>
                    <span>{{ number_format($total, 2) }} €</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; color: var(--text-secondary);">
                    <span>Livraison</span>
                    <span style="color: var(--success); font-weight: 600;">Gratuite (Promo)</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                    <span style="font-size: 1.2rem; font-weight: 700;">Total TTC</span>
                    <span style="font-size: 1.8rem; font-weight: 800; color: white;">{{ number_format($total, 2) }} €</span>
                </div>

                @auth
                    <form action="{{ route('client.commande.valider') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Notes pour la commande (optionnel)</label>
                            <textarea name="notes_client" class="form-control" rows="2" placeholder="Ex: Livraison au garage, pièce à côté de..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" style="width: 100%; font-size: 1.1rem; padding: 1rem;">✅ Valider la commande</button>
                    </form>
                @else
                    <div class="alert alert-error" style="text-align: center; margin-bottom: 1rem;">
                        Vous devez être connecté pour passer ou valider une commande.
                    </div>
                    <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%;">👤 Se connecter pour payer</a>
                @endauth
            </div>
        </div>
    </div>
@endif

<style>
    @media (max-width: 900px) {
        div[style*="grid-template-columns: 1fr 350px"] { grid-template-columns: 1fr; }
    }
</style>
@endsection
