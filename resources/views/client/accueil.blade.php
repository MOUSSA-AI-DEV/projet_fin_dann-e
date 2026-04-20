@extends('layouts.client')

@section('title', 'Accueil')

@section('styles')
<style>
    .hero {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.6)), url('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80') center/cover;
        border-radius: 16px;
        padding: 5rem 3rem;
        text-align: center;
        margin-bottom: 4rem;
        border: 1px solid var(--border-color);
    }
    .hero h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 1rem; color: white; }
    .hero h1 span { color: var(--accent); }
    .hero p { font-size: 1.2rem; color: #cbd5e1; max-width: 600px; margin: 0 auto 2rem; }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 4rem;
    }
    .category-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    .category-card:hover {
        border-color: var(--accent);
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(220, 38, 38, 0.1);
    }
    .category-icon { font-size: 3rem; margin-bottom: 0.5rem; }
    .category-name { font-weight: 700; font-size: 1.1rem; color: white; }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .product-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }
    .product-card:hover {
        border-color: var(--text-secondary);
        transform: translateY(-3px);
    }
    .product-img {
        height: 200px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .product-img img { max-width: 100%; max-height: 100%; object-fit: contain; padding: 1rem; }
    .product-info { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
    .product-brand { font-size: 0.8rem; color: var(--accent); font-weight: 700; text-transform: uppercase; margin-bottom: 0.2rem; }
    .product-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .product-price { font-size: 1.4rem; font-weight: 800; color: white; margin-top: auto; padding-top: 1rem; }
</style>
@endsection

@section('content')

    <div class="hero">
        <h1>Trouvez la <span>Bonne Pièce</span></h1>
        <p>Le plus grand catalogue de pièces automobiles avec livraison sous 24h. Qualité certifiée et prix imbattables.</p>
        <a href="{{ route('client.voiture.index') }}" class="btn btn-primary" style="font-size: 1.1rem;">🚗 Rechercher par mon véhicule</a>
    </div>

    <h2 class="section-title">Catégories Populaires</h2>
    <div class="category-grid">
        @foreach($categories as $cat)
        <a href="{{ route('client.categorie.show', $cat->slug) }}" class="category-card">
            <div class="category-icon">
                @if($cat->image_url)
                    <img src="{{ asset('storage/'.$cat->image_url) }}" alt="{{ $cat->nom }}" style="height: 60px;">
                @else
                    🛠️
                @endif
            </div>
            <div class="category-name">{{ $cat->nom }}</div>
        </a>
        @endforeach
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="section-title" style="margin-bottom: 0;">Nouveautés & Promotions</h2>
        <a href="{{ route('client.catalogue.index') }}" class="text-accent" style="font-weight: 600;">Tout voir →</a>
    </div>
    
    <div class="product-grid">
        @foreach($nouveautes as $piece)
        <a href="{{ route('client.catalogue.show', $piece->slug) }}" class="product-card">
            <div class="product-img">
                @if($piece->images && count($piece->images) > 0)
                    <img src="{{ asset('storage/'.$piece->images[0]) }}" alt="{{ $piece->nom }}">
                @else
                    <span style="font-size:3rem; color:#ccc;">⚙️</span>
                @endif
            </div>
            <div class="product-info">
                <div class="product-brand">{{ $piece->marque->nom ?? 'Générique' }}</div>
                <div class="product-title">{{ $piece->nom }}</div>
                <div class="product-price">
                    @if($piece->references->count() > 0)
                        À partir de {{ number_format($piece->references->min('prix') ?? $piece->prix, 2) }} €
                    @else
                        {{ number_format($piece->prix, 2) }} €
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>

@endsection
