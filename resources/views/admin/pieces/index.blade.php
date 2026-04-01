<x-app-layout>
    @section('page-title', 'Gestion des Pièces')

    <style>
        /* ── Search bar ── */
        .search-wrap {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 0 0.75rem;
            flex: 1;
            max-width: 420px;
            transition: border-color 0.15s;
        }
        .search-wrap:focus-within { border-color: #2563eb; background: #fff; }
        .search-wrap input {
            border: none; background: transparent; outline: none;
            font-size: 0.875rem; padding: 0.55rem 0; flex: 1; color: #1e293b;
        }
        .search-wrap .search-icon { color: #94a3b8; font-size: 1rem; }

        .btn-search {
            padding: 0.55rem 1.1rem;
            background: #1e293b; color: white;
            border: none; border-radius: 8px;
            font-size: 0.875rem; font-weight: 600;
            cursor: pointer; transition: background 0.15s;
        }
        .btn-search:hover { background: #334155; }

        .btn-reset {
            padding: 0.55rem 0.9rem;
            background: #fee2e2; color: #991b1b;
            border: none; border-radius: 8px;
            font-size: 0.8rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            display: flex; align-items: center; gap: 4px;
        }

        tr.ref-row:hover { background: #f8fafc; }
    </style>

    <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0;">Liste des Pièces</h3>
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                @if(session('success'))
                    <div style="background: #dcfce7; color: #166534; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem;">
                        {{ session('success') }}
                    </div>
                @endif
                <a href="{{ route('admin.pieces.create') }}" style="padding: 0.58rem 1.2rem; background: #2563eb; color: white; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; white-space: nowrap;">+ Nouvelle Pièce</a>
            </div>
        </div>

        {{-- ── Search Bar ── --}}
        <form method="GET" action="{{ route('admin.pieces.index') }}" id="search-form" style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" id="search-input" value="{{ $search ?? '' }}" placeholder="Rechercher par nom, catégorie, marque..." autocomplete="off">
            </div>
            <button type="submit" class="btn-search">Rechercher</button>
            <a href="{{ route('admin.pieces.index') }}" id="reset-link" style="display: {{ ($search ?? false) ? 'flex' : 'none' }};" class="btn-reset">✕ Effacer</a>
        </form>

        <div id="table-container">
            @include('admin.pieces._table')
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const container = document.getElementById('table-container');
        const resetLink = document.getElementById('reset-link');
        let debounceTimer;

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = this.value;
                resetLink.style.display = query.trim() !== '' ? 'flex' : 'none';
                performSearch(query);
            }, 300);
        });

        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch(searchInput.value);
        });

        async function performSearch(query) {
            try {
                const response = await fetch(`{{ route('admin.pieces.index') }}?search=${encodeURIComponent(query)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) throw new Error('Network response was not ok');
                const html = await response.text();
                container.innerHTML = html;
                
                const url = new URL(window.location);
                if (query) url.searchParams.set('search', query);
                else url.searchParams.delete('search');
                window.history.replaceState({}, '', url);
            } catch (error) {
                console.error('Search error:', error);
            }
        }
    </script>
</x-app-layout>
