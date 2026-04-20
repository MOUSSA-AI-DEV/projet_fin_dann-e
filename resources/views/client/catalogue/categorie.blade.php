@extends('layouts.client')

@section('title', $category->nom)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="section-title" style="margin-bottom: 0.5rem;">Catégorie : {{ $category->nom }}</h1>
        <p class="text-muted">{{ $pieces->total() }} pièces dans cette catégorie</p>
    </div>
</div>

@if($pieces->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
        @foreach($pieces as $piece)
        <a href="{{ route('client.catalogue.show', $piece->slug) }}" class="card" style="padding: 0; display: flex; flex-direction: column; transition: all 0.3s;">
            <div style="height: 200px; background: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 12px 12px 0 0;">
                @if($piece->images && count($piece->images) > 0)
                    <img src="{{ asset('storage/'.$piece->images[0]) }}" alt="{{ $piece->nom }}" style="max-width: 100%; max-height: 100%; object-fit: contain; padding: 1rem;">
                @else
                    <span style="font-size:3rem; color:#ccc;">⚙️</span>
                @endif
            </div>
            <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                <div style="font-size: 0.8rem; color: var(--accent); font-weight: 700; text-transform: uppercase; margin-bottom: 0.2rem;">{{ $piece->marque->nom ?? 'Générique' }}</div>
                <div style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $piece->nom }}</div>
                <div style="font-size: 1.4rem; font-weight: 800; color: white; margin-top: auto; padding-top: 1rem;">
                    <span style="font-size:0.8rem; color:var(--text-secondary); display:block; font-weight:500;">À partir de</span>
                    {{ number_format($piece->prix, 2) }} €
                </div>
            </div>
        </a>
        @endforeach
    </div>
    
    <div style="margin-top: 3rem; display: flex; justify-content: center;">
        {{ $pieces->links() }}
    </div>
@else
    <div class="card" style="text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">🔍</div>
        <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Aucune pièce trouvée</h3>
        <p class="text-muted">Il n'y a actuellement aucune pièce visible dans cette catégorie.</p>
        <a href="{{ route('client.catalogue.index') }}" class="btn btn-primary" style="margin-top: 1.5rem;">Retour au catalogue</a>
    </div>
@endif
@endsection
