<x-app-layout>
    @section('page-title', 'Modifier l\'Utilisateur')

    <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); max-width: 800px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">Modifier : {{ $user->nom }} {{ $user->prenom }}</h3>
            <a href="{{ route('admin.users.index') }}" style="color: #64748b; text-decoration: none; font-size: 0.875rem;">← Retour à la liste</a>
        </div>

        @if($errors->any())
            <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.875rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" 
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" 
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}" 
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Rôle</label>
                <select name="role" style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem; background: white;">
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Utilisateur (User)</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur (Admin)</option>
                    <option value="garage" {{ old('role', $user->role) == 'garage' ? 'selected' : '' }}>Garage</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Adresse de livraison</label>
                <input type="text" name="adresse_livraison" value="{{ old('adresse_livraison', $user->adresse_livraison) }}" 
                       style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Code Postal</label>
                    <input type="text" name="code_postal" value="{{ old('code_postal', $user->code_postal) }}" 
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Ville</label>
                    <input type="text" name="ville" value="{{ old('ville', $user->ville) }}" 
                           style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('admin.users.index') }}" style="padding: 0.75rem 1.5rem; background: #f1f5f9; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.9rem; font-weight: 600;">Annuler</a>
                <button type="submit" style="padding: 0.75rem 1.5rem; background: #2563eb; color: white; border: none; border-radius: 6px; font-size: 0.9rem; font-weight: 600; cursor: pointer;">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</x-app-layout>
