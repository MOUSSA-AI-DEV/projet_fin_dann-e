@extends('layouts.client')

@section('title', 'Toutes les pièces')

@section('styles')
<style>
    .layout-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2rem;
        align-items: start;
    }
    
    .filter-sidebar {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        position: sticky;
        top: 100px;
    }
    .filter-title { font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1rem; }
    
    .filter-list { list-style: none; margin-bottom: 2rem; }
    .filter-list li { margin-bottom: 0.5rem; }
    .filter-list label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: var(--text-secondary); transition: color 0.2s; }
    .filter-list label:hover { color: white; }
    .filter-list input[type="radio"] { accent-color: var(--accent); }

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
    .product-price { font-size: 1.4rem; font-weight: 800; color: white; margin-top: auto; padding-top: 1rem; display: flex; justify-content: space-between; align-items: center; }

    @media (max-width: 900px) {
        .layout-grid { grid-template-columns: 1fr; }
        .filter-sidebar { position: static; }
    }
</style>
@endsection

@section('content')

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="section-title" style="margin-bottom: 0.5rem;">
                @if(isset($searchQuery))
                    Recherche : "{{ $searchQuery }}"
                @else
                    Catalogue des Pièces
                @endif
            </h1>
            <p class="text-muted">{{ $pieces->total() }} résultats trouvés</p>
        </div>
    </div>

    <div class="layout-grid">
        <aside class="filter-sidebar">
            <form action="{{ route('client.catalogue.index') }}" method="GET" id="filterForm">
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                <div class="filter-title">Catégories</div>
                <ul class="filter-list">
                    <li>
                        <label>
                            <input type="radio" name="categorie" value="" {{ !request('categorie') ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                            Toutes les catégories
                        </label>
                    </li>
                    @foreach($categories as $cat)
                    <li>
                        <label>
                            <input type="radio" name="categorie" value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                            {{ $cat->nom }}
                        </label>
                    </li>
                    @endforeach
                </ul>

                <div class="filter-title">Marques</div>
                <ul class="filter-list">
                    <li>
                        <label>
                            <input type="radio" name="marque" value="" {{ !request('marque') ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                            Toutes les marques
                        </label>
                    </li>
                    @foreach($marques as $marque)
                    <li>
                        <label>
                            <input type="radio" name="marque" value="{{ $marque->id }}" {{ request('marque') == $marque->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                            {{ $marque->nom }}
                        </label>
                    </li>
                    @endforeach
                </ul>

                <a href="{{ route('client.catalogue.index') }}" class="btn btn-outline" style="width: 100%; border-color: var(--border-color); color: var(--text-secondary);">Réinitialiser</a>
            </form>
        </aside>

        <div>
            @if($pieces->count() > 0)
                <div class="product-grid">
                    @foreach($pieces as $piece)
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
                                <div>
                                    <span style="font-size:0.8rem; color:var(--text-secondary); display:block; font-weight:500;">À partir de</span>
                                    {{ number_format($piece->prix, 2) }} €
                                </div>
                                <span class="btn btn-sm btn-outline">Détails</span>
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
                    <p class="text-muted">Essayez de modifier vos critères de recherche ou de réduire les filtres.</p>
                </div>
            @endif
        </div>
    </div>

@endsection
