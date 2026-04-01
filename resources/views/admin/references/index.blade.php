<x-app-layout>
    @section('page-title', 'Gestion des Références')

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

        /* ── Search result highlight ── */
        .search-result-banner {
            background: #eff6ff; border: 1px solid #bfdbfe;
            color: #1d4ed8; border-radius: 8px;
            padding: 0.6rem 1rem; font-size: 0.875rem; font-weight: 500;
            margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;
        }

        /* ── Voitures pill list ── */
        .voiture-pills { display: flex; flex-wrap: wrap; gap: 4px; }
        .pill {
            display: inline-flex; align-items: center; gap: 3px;
            padding: 2px 8px; border-radius: 20px;
            font-size: 0.7rem; font-weight: 600; white-space: nowrap;
        }
        .pill-blue  { background: #eff6ff; color: #2563eb; }
        .pill-gray  { background: #f1f5f9; color: #64748b; }
        .pill-green { background: #dcfce7; color: #166534; }
        .pill-red   { background: #fee2e2; color: #991b1b; }

        /* ── Piece chip ── */
        .piece-chip {
            display: inline-flex; align-items: center; gap: 5px;
            background: #fafaf9; border: 1px solid #e7e5e4;
            border-radius: 6px; padding: 3px 8px; font-size: 0.78rem;
        }
        .piece-chip .piece-icon { font-size: 0.9rem; }

        tr.ref-row:hover { background: #f8fafc; }

        /* expand/collapse rows */
        .voiture-expand-btn {
            background: none; border: none; cursor: pointer;
            color: #2563eb; font-size: 0.72rem; font-weight: 700;
            padding: 0; text-decoration: underline; white-space: nowrap;
        }
        .voiture-list-hidden { display: none; }
    </style>

    <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0;">Liste des Références</h3>
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                @if(session('success'))
                    <div style="background: #dcfce7; color: #166534; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem;">
                        {{ session('success') }}
                    </div>
                @endif
                <a href="{{ route('admin.references.create') }}" style="padding: 0.58rem 1.2rem; background: #2563eb; color: white; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; white-space: nowrap;">+ Nouvelle Référence</a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.references.index') }}" id="search-form" style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" id="search-input" value="{{ $search }}" placeholder="Rechercher par code, nom, pièce, marque, modèle..." autocomplete="off">
            </div>
            <button type="submit" class="btn-search">Rechercher</button>
            <a href="{{ route('admin.references.index') }}" id="reset-link" style="display: {{ $search ? 'flex' : 'none' }};" class="btn-reset">✕ Effacer</a>
        </form>

        <div id="table-container">
            @include('admin.references._table')
        </div>
    </div>

    <script>
        function toggleExtra(id, btn) {
            const extra = document.getElementById('extra-' + id);
            const hidden = extra.classList.contains('voiture-list-hidden');
            if (hidden) {
                extra.classList.remove('voiture-list-hidden');
                extra.style.display = 'flex';
                extra.style.flexWrap = 'wrap';
                extra.style.gap = '4px';
                extra.style.marginTop = '4px';
                btn.textContent = 'Réduire';
            } else {
                extra.classList.add('voiture-list-hidden');
                extra.style.display = 'none';
                const count = extra.querySelectorAll('.pill').length;
                btn.textContent = '+' + count + ' de plus';
            }
        }

        const searchInput = document.getElementById('search-input');
        const container = document.getElementById('table-container');
        const resetLink = document.getElementById('reset-link');
        let debounceTimer;

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = this.value;
                
                // Show/hide reset button
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
                const response = await fetch(`{{ route('admin.references.index') }}?search=${encodeURIComponent(query)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) throw new Error('Network response was not ok');
                const html = await response.text();
                container.innerHTML = html;
                
                // Update URL without reloading
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
