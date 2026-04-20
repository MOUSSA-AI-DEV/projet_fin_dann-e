<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Application') }} - @yield('title', 'Dashboard')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #000000;
            --sidebar-hover: #262626;
            --sidebar-active: #ffffff;
            --sidebar-text: #a3a3a3;
            --sidebar-text-active: #000000;
            --topbar-height: 60px;
            --accent: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: transform 0.3s ease;
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
            background: #ffffff;
            border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            color: #000000; font-weight: 800; font-size: 1.2rem;
            flex-shrink: 0;
        }
        .sidebar-logo span {
            color: #f8fafc;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: 0.3px;
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
            background: #ffffff;
            color: #000000;
            font-weight: 700;
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
        }

        /* ── TOPBAR ── */
        .topbar {
            height: var(--topbar-height);
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 1.1rem; font-weight: 600; color: #1e293b; }
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
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .mobile-toggle { display: flex; }
        }
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
                <span class="nav-icon">🏠</span> Dashboard
            </a>

            @if(Auth::user()->role === 'admin')
            <div class="nav-section-title">Administration</div>


            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span> Utilisateurs
            </a>
            @endif

            <div class="nav-section-title">Catalogue</div>

            <a href="{{ route('admin.pieces.index') }}" class="nav-item {{ request()->routeIs('admin.pieces.*') ? 'active' : '' }}">
                <span class="nav-icon">🔩</span> Pièces
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="nav-icon">📁</span> Catégories
            </a>
            <a href="{{ route('admin.marques.index') }}" class="nav-item {{ request()->routeIs('admin.marques.*') ? 'active' : '' }}">
                <span class="nav-icon">🏷️</span> Marques
            </a>
            <a href="{{ route('admin.voitures.index') }}" class="nav-item {{ request()->routeIs('admin.voitures.*') ? 'active' : '' }}">
                <span class="nav-icon">🚗</span> Voitures
            </a>
            <a href="{{ route('admin.references.index') }}" class="nav-item {{ request()->routeIs('admin.references.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span> Références
            </a>

            <div class="nav-section-title">Ventes & Commandes</div>

            <a href="{{ route('admin.commandes.index') }}" class="nav-item {{ request()->routeIs('admin.commandes.*') ? 'active' : '' }}">
                <span class="nav-icon">📦</span> Commandes
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
                    <span>🚪</span> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- ── MAIN ── --}}
    <div class="main-wrapper">
        <header class="topbar">
            <div style="display:flex; align-items:center; gap:1rem;">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
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

</body>
</html>
