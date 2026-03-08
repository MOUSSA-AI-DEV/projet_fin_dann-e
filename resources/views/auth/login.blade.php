<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f1f5f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; margin-bottom: 1.5rem; color: #1e293b; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.4rem; font-weight: bold; font-size: 0.9rem; color: #374151; }
        input { width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.95rem; }
        input:focus { outline: none; border-color: #2563eb; }
        .alert { background: #fee2e2; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1rem; font-size: 0.9rem; }
        .btn { width: 100%; background: #2563eb; color: white; padding: 0.7rem; border: none; border-radius: 4px; font-weight: bold; font-size: 1rem; cursor: pointer; margin-top: 0.5rem; }
        .btn:hover { background: #1d4ed8; }
        .link-row { margin-top: 1rem; text-align: center; font-size: 0.9rem; }
        .link-row a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Connexion</h2>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Se connecter</button>
        </form>

        <div class="link-row">
            <a href="{{ route('register') }}">Pas encore inscrit ? Créer un compte</a>
        </div>
    </div>
</body>
</html>
