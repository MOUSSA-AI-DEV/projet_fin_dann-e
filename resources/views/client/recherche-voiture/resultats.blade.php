@extends('layouts.client')
@section('title', 'Pièces Compatibles')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
    <div>
        <h1 class="section-title" style="margin-bottom: 0.5rem;">Pièces Compatibles</h1>
        <p style="font-size: 1.1rem; color: var(--text-secondary); display: flex; align-items: center; gap: 0.5rem;">
            🚗 <strong>{{ $search['marque'] }} - {{ $search['modele'] }}</strong> 
            {{ isset($search['motorisation']) && $search['motorisation'] ? ' ('.$search['motorisation'].')' : '' }}
        </p>
    </div>
    <div>
        <a href="{{ route('client.voiture.index') }}" class="btn btn-outline" style="background: var(--bg-secondary);">Modifier le véhicule</a>
    </div>
</div>

@if($references->count() > 0)
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid var(--success); color: #34d399; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
        <span style="font-size: 1.5rem;">✅</span>
        <span style="font-weight: 500;">Ces {{ $references->total() }} références sont garanties 100% compatibles avec votre véhicule.</span>
    </div>

    <!-- On affiche les références directement, car la compatibilité se fait par référence -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
        @foreach($references as $ref)
        <div class="card" style="padding: 0; display: flex; flex-direction: column; transition: all 0.3s; position: relative;">
            <div style="height: 180px; background: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 12px 12px 0 0; padding: 1rem;">
                @if($ref->images && count($ref->images) > 0)
                    <img src="{{ asset('storage/'.$ref->images[0]) }}" alt="{{ $ref->nom }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                @elseif(isset($ref->piece->images) && count($ref->piece->images) > 0)
                    <img src="{{ asset('storage/'.$ref->piece->images[0]) }}" alt="{{ $ref->nom }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                @else
                    <span style="font-size:3rem; color:#ccc;">⚙️</span>
                @endif
            </div>
            
            <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                    Famille : <a href="{{ route('client.catalogue.show', $ref->piece->slug) }}" style="color:var(--text-primary); text-decoration:underline;">{{ $ref->piece->nom }}</a>
                </div>
                
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.3rem;">{{ $ref->nom }}</h3>
                <div style="font-family: monospace; color: var(--accent); font-weight: 600; margin-bottom: 1rem;">Réf OEM: {{ $ref->reference }}</div>
                
                <div style="margin-top: auto; display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 1.3rem; font-weight: 800; color: white;">{{ number_format($ref->prix ?? $ref->piece->prix, 2) }} €</div>
                    @if($ref->stock > 0)
                        <form action="{{ route('client.panier.ajouter') }}" method="POST">
                            @csrf
                            <input type="hidden" name="reference_id" value="{{ $ref->id }}">
                            <input type="hidden" name="quantite" value="1">
                            <button type="submit" class="btn btn-sm btn-primary">🛒 Ajouter</button>
                        </form>
                    @else
                        <span class="badge badge-red">Rupture</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div style="margin-top: 3rem; display: flex; justify-content: center;">
        {{ $references->appends(request()->query())->links() }}
    </div>
@else
    <div class="card" style="text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">🤷‍♂️</div>
        <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Aucune pièce compatible trouvée</h3>
        <p class="text-muted">Nous n'avons pas encore de pièces certifiées pour ce véhicule dans notre base de données.</p>
    </div>
@endif
@endsection
