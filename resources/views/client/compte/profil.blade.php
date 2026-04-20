@extends('layouts.client')
@section('title', 'Mon Profil')

@section('content')
<h1 class="section-title">Mon Profil</h1>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <form action="{{ route('client.compte.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" class="form-control" required>
                    @error('prenom')<span style="color:var(--accent);font-size:0.8rem;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" class="form-control" required>
                    @error('nom')<span style="color:var(--accent);font-size:0.8rem;">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email (non modifiable)</label>
                <input type="email" value="{{ $user->email }}" class="form-control" disabled style="opacity: 0.7; cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}" class="form-control">
                @error('telephone')<span style="color:var(--accent);font-size:0.8rem;">{{ $message }}</span>@enderror
            </div>

            <h3 style="margin: 2rem 0 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-color);">Adresse de livraison par défaut</h3>

            <div class="form-group">
                <label class="form-label">Adresse (Rue, Numéro...)</label>
                <input type="text" name="adresse_livraison" value="{{ old('adresse_livraison', $user->adresse_livraison) }}" class="form-control">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Code Postal</label>
                    <input type="text" name="code_postal" value="{{ old('code_postal', $user->code_postal) }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Ville</label>
                    <input type="text" name="ville" value="{{ old('ville', $user->ville) }}" class="form-control">
                </div>
            </div>

            <div style="margin-top: 2rem; text-align: right;">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>
@endsection
