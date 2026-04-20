@extends('layouts.client')

@section('title', $piece->nom)

@section('styles')
<style>
    .piece-header {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }
    
    .piece-gallery {
        background: #fff;
        border-radius: 12px;
        padding: 2rem;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .piece-gallery img { max-width: 100%; max-height: 100%; object-fit: contain; }

    .piece-details h1 { font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem; }
    .piece-brand { color: var(--accent); font-weight: 700; text-transform: uppercase; margin-bottom: 1.5rem; display: block; }
    
    .piece-desc { color: var(--text-secondary); line-height: 1.6; margin-bottom: 2rem; }
    
    .specs-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .spec-item {
        background: var(--bg-secondary);
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }
    .spec-label { font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700; margin-bottom: 0.2rem; }
    .spec-value { font-weight: 600; }

    .references-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 1.5rem;
    }
    .references-table th { background: var(--bg-tertiary); padding: 1rem; text-align: left; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; font-size: 0.8rem; }
    .references-table th:first-child { border-radius: 8px 0 0 8px; }
    .references-table th:last-child { border-radius: 0 8px 8px 0; }
    
    .references-table td { padding: 1rem; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
    .ref-code { font-family: monospace; font-size: 1.1rem; color: white; background: var(--bg-secondary); padding: 0.3rem 0.6rem; border-radius: 4px; border: 1px solid var(--border-color); }
    .ref-price { font-size: 1.25rem; font-weight: 800; color: white; }

    @media (max-width: 900px) {
        .piece-header { grid-template-columns: 1fr; }
        .piece-gallery { height: 300px; }
        .references-table { display: block; overflow-x: auto; }
    }
</style>
@endsection

@section('content')

    <div class="piece-header">
        <div class="piece-gallery">
            @if($piece->images && count($piece->images) > 0)
                <img src="{{ asset('storage/'.$piece->images[0]) }}" alt="{{ $piece->nom }}">
            @else
                <span style="font-size:5rem; color:#ccc;">⚙️</span>
            @endif
        </div>

        <div class="piece-details">
            <span class="badge badge-gray" style="margin-bottom: 1rem;">{{ $piece->category->nom ?? '' }}</span>
            <h1>{{ $piece->nom }}</h1>
            <span class="piece-brand">{{ $piece->marque->nom ?? 'Pièce Générique' }}</span>
            
            <p class="piece-desc">
                {{ $piece->description ?: 'Aucune description détaillée pour cette pièce. Veuillez vérifier les références OEM compatibles ci-dessous.' }}
            </p>

            @if($piece->caracteristiques && count((array)$piece->caracteristiques) > 0)
            <div class="specs-grid">
                @foreach($piece->caracteristiques as $key => $val)
                <div class="spec-item">
                    <div class="spec-label">{{ $key }}</div>
                    <div class="spec-value">{{ is_array($val) ? implode(', ', $val) : $val }}</div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <h2 class="section-title">Références Disponibles</h2>
    
    @if($piece->references->count() > 0)
    <div class="card" style="padding: 0; overflow: hidden;">
        <table class="references-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Code OEM</th>
                    <th>Nom commercial</th>
                    <th>Disponibilité</th>
                    <th>Prix net</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($piece->references as $ref)
                <tr>
                    <td>
                        <div style="width: 50px; height: 50px; background: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--border-color); padding: 2px;">
                            @if($ref->images && count($ref->images) > 0)
                                <img src="{{ asset('storage/'.$ref->images[0]) }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            @else
                                <span style="font-size: 1.2rem; filter: grayscale(1);">⚙️</span>
                            @endif
                        </div>
                    </td>
                    <td><span class="ref-code">{{ $ref->reference }}</span></td>
                    <td>
                        <div style="font-weight: 600;">{{ $ref->nom }}</div>
                        @if($ref->garantie)
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">Garantie: {{ $ref->garantie }}</div>
                        @endif
                    </td>
                    <td>
                        @if($ref->stock > 10)
                            <span class="badge badge-green">En Stock ({{ $ref->stock }})</span>
                        @elseif($ref->stock > 0)
                            <span class="badge badge-blue">Stock Limité ({{ $ref->stock }})</span>
                        @else
                            <span class="badge badge-red">Rupture</span>
                        @endif
                    </td>
                    <td class="ref-price">{{ number_format($ref->prix ?? $piece->prix, 2) }} €</td>
                    <td style="text-align: right;">
                        <form action="{{ route('client.panier.ajouter') }}" method="POST" style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            @csrf
                            <input type="hidden" name="reference_id" value="{{ $ref->id }}">
                            <input type="number" name="quantite" value="1" min="1" max="{{ $ref->stock > 0 ? $ref->stock : 1 }}" class="form-control" style="width: 70px; padding: 0.5rem; text-align: center;" {{ $ref->stock <= 0 ? 'disabled' : '' }}>
                            <button type="submit" class="btn btn-primary" {{ $ref->stock <= 0 ? 'disabled' : '' }} style="{{ $ref->stock <= 0 ? 'opacity:0.5;cursor:not-allowed;' : '' }}">
                                🛒 Ajouter
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="alert alert-error">Aucune référence n'est actuellement disponible pour cette pièce.</div>
    @endif

@endsection
