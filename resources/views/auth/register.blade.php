<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f1f5f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem 0; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h2 { text-align: center; margin-bottom: 1.5rem; color: #1e293b; }
        .form-row { display: flex; gap: 1rem; }
        .form-row .form-group { flex: 1; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.4rem; font-weight: bold; font-size: 0.85rem; color: #374151; }
        input { width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.9rem; }
        input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,0.2); }
        .section-title { font-size: 0.75rem; font-weight: bold; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 1.25rem 0 0.75rem; border-top: 1px solid #e5e7eb; padding-top: 1rem; }
        .alert { background: #fee2e2; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1rem; font-size: 0.9rem; }
        .btn { width: 100%; background: #10b981; color: white; padding: 0.7rem; border: none; border-radius: 4px; font-weight: bold; font-size: 1rem; cursor: pointer; margin-top: 0.75rem; }
        .btn:hover { background: #059669; }
        .link-row { margin-top: 1rem; text-align: center; font-size: 0.9rem; }
        .link-row a { color: #2563eb; text-decoration: none; }
        .optional { font-weight: normal; color: #9ca3af; font-size: 0.8rem; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Inscription</h2>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Identité --}}
            <div class="form-row">
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}" required autofocus>
                </div>
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input id="nom" type="text" name="nom" value="{{ old('nom') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone <span class="optional">(optionnel)</span></label>
                <input id="telephone" type="tel" name="telephone" value="{{ old('telephone') }}">
            </div>

            {{-- Adresse --}}
            <div class="section-title">Adresse de livraison <span class="optional">(optionnelle)</span></div>

            <div class="form-group">
                <label for="adresse_livraison">Adresse</label>
                <input id="adresse_livraison" type="text" name="adresse_livraison" value="{{ old('adresse_livraison') }}" placeholder="Ex: 12 rue des exemples">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="code_postal">Code postal</label>
                    <input id="code_postal" type="text" name="code_postal" value="{{ old('code_postal') }}" placeholder="Ex: 75001">
                </div>
                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input id="ville" type="text" name="ville" value="{{ old('ville') }}" placeholder="Ex: Paris">
                </div>
            </div>

            {{-- Mot de passe --}}
            <div class="section-title">Sécurité</div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn">S'inscrire</button>
        </form>

        <div class="link-row">
            <a href="{{ route('login') }}">Déjà un compte ? Se connecter</a>
        </div>
    </div>
</body>
</html>
