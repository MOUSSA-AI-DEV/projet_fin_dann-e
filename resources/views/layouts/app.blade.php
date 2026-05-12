<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Application') }} - @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/theme-fluide.css') }}">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 78px;
            --sidebar-bg: #0F172A; 
            --sidebar-hover: #1E293B;
            --sidebar-active: #3B82F6;
            --sidebar-text: #94A3B8;
            --sidebar-text-active: #FFFFFF;
            --topbar-height: 64px;
            --topbar-bg: #FFFFFF;
            --accent: #2563EB;
            --accent-hover: #1D4ED8;
            --page-bg: #F8FAFC;
            --card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        }





        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: var(--page-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
        }


        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #1E3A8A 100%);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            overflow-x: hidden;
        }

        body.collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }


        .sidebar-logo {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid #262626;
            gap: 0.75rem;
        }
        .sidebar-logo .logo-icon {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            color: #ffffff; font-weight: 800; font-size: 1.2rem;
            flex-shrink: 0;
        }

        .sidebar-logo span {
            color: #f8fafc;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: 0.3px;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        body.collapsed .sidebar-logo span,
        body.collapsed .nav-section-title,
        body.collapsed .nav-text,
        body.collapsed .nav-badge,
        body.collapsed .user-info,
        body.collapsed .btn-logout span:last-child {
            opacity: 0;
            pointer-events: none;
            width: 0;
            display: none;
        }

        .sidebar-nav { flex: 1; padding: 1rem 0; overflow-y: auto; }

        .nav-section-title {
            color: #525252;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.75rem 1.5rem 0.4rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
            border-radius: 0;
            position: relative;
        }
        .nav-item:hover {
            background: var(--sidebar-hover);
            color: var(--sidebar-text-active);
        }
        .nav-item.active {
            background: var(--sidebar-active);
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }


        .nav-item .nav-icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }
        .nav-badge {
            margin-left: auto;
            background: #ffffff;
            color: #000000;
            font-size: 0.7rem;
            padding: 0.1rem 0.45rem;
            border-radius: 4px;
            font-weight: 800;
        }

        .sidebar-footer {
            border-top: 1px solid #262626;
            padding: 1rem 1.5rem;
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }
        .avatar {
            width: 36px; height: 36px;
            background: #ffffff;
            border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            color: #000000; font-weight: 800; font-size: 0.85rem;
            flex-shrink: 0;
            border: 1px solid #ccc;
        }
        .user-info .user-name { color: #f8fafc; font-size: 0.875rem; font-weight: 600; }
        .user-info .user-role {
            color: #d4d4d4; font-size: 0.73rem;
            background: #262626; padding: 1px 8px;
            border-radius: 4px; display: inline-block; margin-top: 2px;
            text-transform: uppercase; font-weight: 700;
        }
        .user-info .user-role.admin { background: #404040; color: #ffffff; }
        .user-info .user-role.garage { background: #404040; color: #ffffff; }

        .btn-logout {
            display: flex; align-items: center; gap: 0.5rem;
            width: 100%; padding: 0.5rem 0.75rem;
            background: rgba(239,68,68,0.1); color: #f87171;
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 6px; font-size: 0.82rem;
            cursor: pointer; text-align: left;
            transition: all 0.15s ease;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.2); }

        /* ── MAIN LAYOUT ── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.collapsed .main-wrapper {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* ── TOPBAR ── */
        .topbar {
            height: var(--topbar-height);
            background: var(--topbar-bg);
            border-bottom: 2px solid var(--accent);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky; top: 0; z-index: 50;
            color: #1E293B;
            box-shadow: var(--card-shadow);
        }
        .topbar-title { font-size: 1.1rem; font-weight: 700; color: #0F172A; }


        .topbar-actions { display: flex; align-items: center; gap: 1rem; }
        .topbar-actions .notification-btn {
            width: 36px; height: 36px;
            background: #f1f5f9; border: none; border-radius: 8px;
            cursor: pointer; font-size: 1rem;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── PAGE CONTENT ── */
        .page-content { padding: 2rem; flex: 1; }

        /* ── MOBILE TOGGLE ── */
        .mobile-toggle {
            display: none;
            background: none; border: none;
            font-size: 1.4rem; cursor: pointer; color: #475569;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .mobile-toggle { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #f1f5f9; border-radius: 8px; }
            
            .sidebar-overlay {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(15, 23, 42, 0.5);
                backdrop-filter: blur(2px);
                z-index: 90;
                display: none;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .sidebar-overlay.show {
                display: block;
                opacity: 1;
            }
        }

        /* ── PAGINATION PREMIUM ── */
        .pagination {
            display: flex;
            gap: 0.4rem;
            list-style: none;
            padding: 0;
            margin: 1rem 0;
            justify-content: center;
        }
        .page-item { margin: 0; }
        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 0.5rem;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #475569;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .page-link:hover {
            border-color: #2563eb;
            color: #2563eb;
            background: #eff6ff;
            transform: translateY(-1px);
        }
        .page-item.active .page-link {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
        }
        .page-item.disabled .page-link {
            background: #f8fafc;
            color: #cbd5e1;
            border-color: #f1f5f9;
            cursor: not-allowed;
        }
        .pagination .hidden { display: none; }
        .pagination svg { width: 1.25rem; height: 1.25rem; }
    </style>
</head>
<body>

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">A</div>
            <span>{{ config('app.name', 'App') }}</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Principal</div>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span> <span class="nav-text">Dashboard</span>
            </a>

            @if(Auth::user()->role === 'admin')
            <div class="nav-section-title">Administration</div>


            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span> <span class="nav-text">Utilisateurs</span>
            </a>
            @endif

            <div class="nav-section-title">Catalogue</div>

            <a href="{{ route('admin.pieces.index') }}" class="nav-item {{ request()->routeIs('admin.pieces.*') ? 'active' : '' }}">
                <span class="nav-icon">🔩</span> <span class="nav-text">Pièces</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="nav-icon">📁</span> <span class="nav-text">Catégories</span>
            </a>
            <a href="{{ route('admin.marques.index') }}" class="nav-item {{ request()->routeIs('admin.marques.*') ? 'active' : '' }}">
                <span class="nav-icon">🏷️</span> <span class="nav-text">Marques</span>
            </a>
            <a href="{{ route('admin.voitures.index') }}" class="nav-item {{ request()->routeIs('admin.voitures.*') ? 'active' : '' }}">
                <span class="nav-icon">🚗</span> <span class="nav-text">Voitures</span>
            </a>
            <a href="{{ route('admin.references.index') }}" class="nav-item {{ request()->routeIs('admin.references.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span> <span class="nav-text">Références</span>
            </a>
            <a href="{{ route('admin.factures-fournisseur.index') }}" class="nav-item {{ request()->routeIs('admin.factures-fournisseur.*') ? 'active' : '' }}">
                <span class="nav-icon">📑</span> <span class="nav-text">Factures Fournisseur</span>
            </a>

            <div class="nav-section-title">Ventes & Commandes</div>

            <a href="{{ route('admin.commandes.index') }}" class="nav-item {{ request()->routeIs('admin.commandes.*') ? 'active' : '' }}">
                <span class="nav-icon">📦</span> <span class="nav-text">Commandes</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="avatar">
                    {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                    <span class="user-role {{ Auth::user()->role }}">{{ Auth::user()->role }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <span class="nav-icon">🚪</span> <span>Déconnexion</span>
                </button>
            </form>
        </div>  
    </aside>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    
    {{-- ── MAIN ── --}}
    <div class="main-wrapper">

        <header class="topbar">
            <div style="display:flex; align-items:center; gap:1.5rem;">
                <button class="mobile-toggle" id="mobile-toggle">☰</button>
                <button id="collapse-toggle" style="background:none; border:none; color:#64748b; cursor:pointer; font-size:1.2rem; display:flex; align-items:center; justify-content:center;">
                    <span id="collapse-icon">◀</span>
                </button>
                <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
            </div>

            <div class="topbar-actions">
                <button class="notification-btn">🔔</button>
            </div>
        </header>

        <main class="page-content">
            @if(session('success'))
                <div style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #bbf7d0; font-weight: 600;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #fecaca; font-weight: 600;">
                    ❌ {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: #fff1f2; color: #be123c; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #fecdd3;">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li style="font-weight: 600;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggle = document.getElementById('mobile-toggle');
        const collapseBtn = document.getElementById('collapse-toggle');
        const collapseIcon = document.getElementById('collapse-icon');

        // Restore state
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            document.body.classList.add('collapsed');
            if (collapseIcon) collapseIcon.textContent = '▶';
        }

        function toggleSidebar() {
            const isOpen = sidebar.classList.toggle('open');
            overlay.classList.toggle('show', isOpen);
            document.body.style.overflow = isOpen ? 'hidden' : '';
        }

        if (toggle) toggle.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);

        if (collapseBtn) {
            collapseBtn.addEventListener('click', () => {
                const isCollapsed = document.body.classList.toggle('collapsed');
                localStorage.setItem('sidebar-collapsed', isCollapsed);
                collapseIcon.textContent = isCollapsed ? '▶' : '◀';
            });
        }

        // Auto-close on resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768 && sidebar.classList.contains('open')) {
                toggleSidebar();
            }
        });
    </script>
</body>
</html>

