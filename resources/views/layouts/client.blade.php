<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AutoParts') }} - @yield('title', 'Boutique')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a; /* slate-900 */
            --bg-secondary: #1e293b; /* slate-800 */
            --bg-tertiary: #334155; /* slate-700 */
            --text-primary: #f8fafc; /* slate-50 */
            --text-secondary: #94a3b8; /* slate-400 */
            --accent: #dc2626; /* red-600 */
            --accent-hover: #b91c1c; /* red-700 */
            --border-color: #334155;
            --success: #10b981;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-color); color: var(--text-primary); min-height: 100vh; display: flex; flex-direction: column; }
        a { text-decoration: none; color: inherit; }
        button, input, select { font-family: inherit; }

        /* HEADER */
        header { background: var(--bg-secondary); border-bottom: 1px solid var(--border-color); position: sticky; top: 0; z-index: 100; }
        .header-top { display: flex; justify-content: space-between; align-items: center; padding: 1rem 5%; height: 80px; }
        
        .logo { font-size: 1.5rem; font-weight: 800; color: white; display: flex; align-items: center; gap: 0.5rem; }
        .logo span { color: var(--accent); }

        .search-bar { flex: 1; max-width: 500px; margin: 0 2rem; position: relative; }
        .search-bar form { display: flex; width: 100%; }
        .search-bar input { width: 100%; padding: 0.75rem 1rem; border-radius: 8px 0 0 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: white; outline: none; }
        .search-bar input:focus { border-color: var(--accent); }
        .search-bar button { padding: 0.75rem 1.5rem; background: var(--accent); border: none; border-radius: 0 8px 8px 0; color: white; cursor: pointer; font-weight: 600; transition: background 0.2s; }
        .search-bar button:hover { background: var(--accent-hover); }

        .header-actions { display: flex; align-items: center; gap: 1.5rem; }
        .action-item { display: flex; flex-direction: column; align-items: center; font-size: 0.8rem; color: var(--text-secondary); transition: color 0.2s; position: relative; }
        .action-item:hover { color: white; }
        .action-icon { font-size: 1.5rem; margin-bottom: 0.2rem; }
        
        .cart-badge { position: absolute; top: -5px; right: -5px; background: var(--accent); color: white; font-size: 0.7rem; font-weight: 800; border-radius: 50%; min-width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; }

        .nav-links { display: flex; gap: 2rem; padding: 0.75rem 5%; background: var(--bg-color); border-bottom: 1px solid var(--border-color); overflow-x: auto; }
        .nav-links a { color: var(--text-primary); font-weight: 500; font-size: 0.95rem; white-space: nowrap; transition: color 0.2s; }
        .nav-links a:hover { color: var(--accent); }

        .hamburger { display: none; background: none; border: none; color: white; font-size: 1.8rem; cursor: pointer; }

        /* BUTTONS */
        .btn { display: inline-flex; justify-content: center; align-items: center; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; gap: 0.5rem; }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: var(--accent-hover); transform: translateY(-1px); }
        .btn-outline { background: transparent; border: 1px solid var(--border-color); color: white; }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-success { background: var(--success); color: white; }
        .btn-success:hover { filter: brightness(1.1); }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.85rem; border-radius: 6px; }

        /* MAIN CONTENT */
        main { flex: 1; padding: 2rem 5%; }
        
        .section-title { font-size: 1.8rem; font-weight: 800; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
        .section-title::before { content: ''; width: 4px; height: 24px; background: var(--accent); border-radius: 4px; display: inline-block; }

        /* FOOTER */
        footer { background: var(--bg-secondary); border-top: 1px solid var(--border-color); padding: 3rem 5% 1rem; margin-top: auto; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-bottom: 2rem; }
        .footer-col h4 { color: white; font-size: 1.1rem; margin-bottom: 1rem; font-weight: 700; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 0.5rem; color: var(--text-secondary); }
        .footer-col ul li a:hover { color: var(--accent); }
        .footer-bottom { text-align: center; color: var(--text-secondary); font-size: 0.85rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); }

        /* CARDS & CONTAINERS */
        .card { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; overflow: hidden; }
        
        /* ALERTS */
        .alert { padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 500; }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid var(--success); color: #34d399; }
        .alert-error { background: rgba(220, 38, 38, 0.1); border: 1px solid var(--accent); color: #f87171; }

        /* FORMS */
        .form-group { margin-bottom: 1.2rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-secondary); font-size: 0.9rem;}
        .form-control { width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: white; outline: none; transition: border-color 0.2s; }
        .form-control:focus { border-color: var(--accent); }

        /* UTILS */
        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .badge-green { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .badge-red { background: rgba(220, 38, 38, 0.15); color: #f87171; }
        .badge-blue { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
        .badge-gray { background: rgba(148, 163, 184, 0.15); color: #94a3b8; }

        .text-accent { color: var(--accent); }
        .text-muted { color: var(--text-secondary); }

        /* DYNAMIC SEARCH */
        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            max-height: 450px;
            overflow-y: auto;
            display: none;
        }
        .search-result-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.2s;
            cursor: pointer;
        }
        .search-result-item:last-child { border-bottom: none; border-radius: 0 0 8px 8px; }
        .search-result-item:hover { background: var(--bg-tertiary); padding-left: 1.25rem; }
        .search-result-img { width: 45px; height: 45px; background: white; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; padding: 2px; }
        .search-result-img img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .search-result-info { flex: 1; overflow: hidden; }
        .search-result-name { font-weight: 600; font-size: 0.9rem; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .search-result-meta { font-size: 0.75rem; color: var(--text-secondary); display: flex; justify-content: space-between; }
        .search-result-price { color: var(--accent); font-weight: 700; }

        @media (max-width: 768px) {
            .search-bar { display: none; }
            .hamburger { display: block; }
            .nav-links { display: none; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <header>
        <div class="header-top">
            <button class="hamburger">☰</button>
            <a href="{{ route('client.accueil') }}" class="logo">Auto<span>Parts</span></a>
            
            <div class="search-bar">
                <form action="{{ route('client.recherche') }}" method="GET" id="main-search-form">
                    <input type="text" name="q" id="search-input" placeholder="Rechercher une pièce, référence OEM..." value="{{ request('q') }}" autocomplete="off">
                    <button type="submit">🔍</button>
                </form>
                <div id="search-results" class="search-results-dropdown"></div>
            </div>

            <div class="header-actions">
                <a href="{{ route('client.voiture.index') }}" class="action-item">
                    <span class="action-icon">🚗</span>
                    <span>Ma Voiture</span>
                </a>
                
                @auth
                    <div style="position: relative;" class="user-menu-wrapper">
                        <a href="{{ route('client.compte.profil') }}" class="action-item">
                            <span class="action-icon">👤</span>
                            <span>{{ Auth::user()->prenom }}</span>
                        </a>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="action-item">
                        <span class="action-icon">👤</span>
                        <span>Connexion</span>
                    </a>
                @endauth
                
                <a href="{{ route('client.panier.index') }}" class="action-item">
                    <div style="position: relative;">
                        <span class="action-icon">🛒</span>
                        <span class="cart-badge" id="cart-badge">{{ count(session('panier', [])) }}</span>
                    </div>
                    <span>Panier</span>
                </a>
                @auth
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="action-item" style="color:var(--accent)">
                        <span class="action-icon">⚙️</span>
                        <span>Panel</span>
                    </a>
                    @endif
                @endauth
            </div>
        </div>
        
        <nav class="nav-links">
            <a href="{{ route('client.accueil') }}">Accueil</a>
            <a href="{{ route('client.catalogue.index') }}">Toutes les pièces</a>
            @php $cats = clone(\App\Models\Category::where('is_active', true)->whereNull('parent_id')->orderBy('position')->take(5)->get()); @endphp
            @foreach($cats as $cat)
                <a href="{{ route('client.categorie.show', $cat->slug) }}">{{ $cat->nom }}</a>
            @endforeach
            <a href="{{ route('client.catalogue.index') }}" style="color: var(--accent); font-weight: 700;">% Promotions</a>
        </nav>
    </header>

    <main>
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <a href="{{ route('client.accueil') }}" class="logo" style="margin-bottom: 1rem;">Auto<span>Parts</span></a>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Votre partenaire de confiance pour des pièces automobiles de qualité premium.</p>
            </div>
            <div class="footer-col">
                <h4>Catalogue</h4>
                <ul>
                    <li><a href="{{ route('client.catalogue.index') }}">Toutes les pièces</a></li>
                    <li><a href="{{ route('client.voiture.index') }}">Recherche par véhicule</a></li>
                    <li><a href="{{ route('client.catalogue.index') }}">Promotions</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Mon Compte</h4>
                <ul>
                    @auth
                        <li><a href="{{ route('client.compte.profil') }}">Profil</a></li>
                        <li><a href="{{ route('client.commande.liste') }}">Mes commandes</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-family:inherit;font-size:1rem;padding:0;text-align:left;">Déconnexion</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Connexion</a></li>
                        <li><a href="{{ route('register') }}">Inscription</a></li>
                    @endauth
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <ul>
                    <li>📞 01 23 45 67 89</li>
                    <li>✉️ contact@autoparts.com</li>
                    <li>📍 123 rue de la Mécanique</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} AutoParts. Tous droits réservés.
        </div>
    </footer>

    @yield('scripts')
    <script>
        function updateCartBadge() {
            fetch('{{ route("client.panier.compter") }}')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('cart-badge').innerText = data.count;
                });
        }

    
        const searchInput = document.getElementById('search-input');
        const searchResults = document.getElementById('search-results');
        let searchTimeout = null;

        searchInput.addEventListener('input', function() {
            const q = this.value;
            
            clearTimeout(searchTimeout);
            
            if (q.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('client.search.suggestions') }}?q=${encodeURIComponent(q)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            let html = '';
                            data.forEach(item => {
                                html += `
                                    <a href="${item.url}" class="search-result-item">
                                        <div class="search-result-img">
                                            ${item.image ? `<img src="${item.image}" alt="">` : `<span style="font-size:1.2rem">⚙️</span>`}
                                        </div>
                                        <div class="search-result-info">
                                            <div class="search-result-name">${item.nom}</div>
                                            <div class="search-result-meta">
                                                <span>${item.marque}</span>
                                                <span class="search-result-price">${item.prix}</span>
                                            </div>
                                        </div>
                                    </a>
                                `;
                            });
                            searchResults.innerHTML = html;
                            searchResults.style.display = 'block';
                        } else {
                            searchResults.style.display = 'none';
                        }
                    });
            }, 300);
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            } 
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.length >= 2 && searchResults.innerHTML !== '') {
                searchResults.style.display = 'block';
            }
        });
    </script>
</body>
</html>
