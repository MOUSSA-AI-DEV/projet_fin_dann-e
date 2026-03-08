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
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #2563eb;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #f8fafc;
            --topbar-height: 60px;
            --accent: #2563eb;
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
            border-bottom: 1px solid #1e293b;
            gap: 0.75rem;
        }
        .sidebar-logo .logo-icon {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: bold; font-size: 1.1rem;
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
            color: #475569;
            font-size: 0.7rem;
            font-weight: 700;
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
            background: rgba(37,99,235,0.15);
            color: #60a5fa;
            border-right: 3px solid var(--accent);
        }
        .nav-item .nav-icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }
        .nav-badge {
            margin-left: auto;
            background: var(--accent);
            color: white;
            font-size: 0.7rem;
            padding: 0.1rem 0.45rem;
            border-radius: 99px;
            font-weight: 700;
        }

        .sidebar-footer {
            border-top: 1px solid #1e293b;
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
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: bold; font-size: 0.85rem;
            flex-shrink: 0;
        }
        .user-info .user-name { color: #f8fafc; font-size: 0.875rem; font-weight: 600; }
        .user-info .user-role {
            color: #475569; font-size: 0.73rem;
            background: #1e293b; padding: 1px 8px;
            border-radius: 99px; display: inline-block; margin-top: 2px;
        }
        .user-info .user-role.admin { background: rgba(37,99,235,0.2); color: #60a5fa; }
        .user-info .user-role.garage { background: rgba(16,185,129,0.2); color: #34d399; }

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

            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span> Dashboard
            </a>

            @if(Auth::user()->role === 'admin')
            <div class="nav-section-title">Administration</div>

            <a href="#" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span> Panel Admin
            </a>

            <a href="#" class="nav-item">
                <span class="nav-icon">👥</span> Utilisateurs
            </a>
            @endif

            <div class="nav-section-title">Catalogue</div>

            <a href="#" class="nav-item">
                <span class="nav-icon">🔩</span> Pièces
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">📁</span> Catégories
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">🏷️</span> Marques
            </a>

            <div class="nav-section-title">Commandes</div>

            <a href="#" class="nav-item">
                <span class="nav-icon">📦</span> Commandes
                <span class="nav-badge">0</span>
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
            {{ $slot }}
        </main>
    </div>

</body>
</html>
