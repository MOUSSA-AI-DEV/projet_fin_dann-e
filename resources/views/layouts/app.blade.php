<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Application') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; min-height: 100vh; }
        nav {
            background: #1e293b;
            color: white;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
        }
        nav a.brand { color: white; font-weight: bold; font-size: 1.2rem; text-decoration: none; }
        nav .nav-links { display: flex; gap: 1rem; align-items: center; }
        nav .nav-links a { color: #cbd5e1; text-decoration: none; font-size: 0.9rem; }
        nav .nav-links a:hover { color: white; }
        nav .user-info { display: flex; align-items: center; gap: 1rem; font-size: 0.9rem; }
        nav .user-info span { color: #94a3b8; }
        nav .btn-logout {
            background: #ef4444; color: white; border: none; padding: 0.4rem 1rem;
            border-radius: 4px; cursor: pointer; font-size: 0.85rem;
        }
        nav .btn-logout:hover { background: #dc2626; }
        .page-header {
            background: white; border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
        }
        .page-header h1 { font-size: 1.25rem; color: #1e293b; }
        .main-content { padding: 2rem; max-width: 1200px; margin: 0 auto; }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('dashboard') }}" class="brand">{{ config('app.name', 'Application') }}</a>

        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Dashboard</a>
        </div>

        @auth
        <div class="user-info">
            <span>{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout">Déconnexion</button>
            </form>
        </div>
        @endauth
    </nav>

    @isset($header)
    <div class="page-header">
        <h1>{{ $header }}</h1>
    </div>
    @endisset

    <main class="main-content">
        {{ $slot }}
    </main>
</body>
</html>
