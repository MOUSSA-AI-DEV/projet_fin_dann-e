@extends('layouts.client')
@section('title', 'Recherche par véhicule')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 class="section-title" style="justify-content: center; margin-bottom: 0.5rem;">Trouvez les pièces pour votre voiture</h1>
        <p class="text-muted">Sélectionnez les caractéristiques de votre véhicule pour voir les pièces 100% compatibles.</p>
    </div>

    <div class="card" style="padding: 2.5rem;">
        <form action="{{ route('client.voiture.resultats') }}" method="GET" id="voiture-form">
            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">1. Marque de la voiture</label>
                    <select name="marque" id="marque" class="form-control" required style="font-size: 1.1rem; padding: 1rem;">
                        <option value="">Sélectionnez la marque</option>
                        @foreach($marques as $marque)
                            <option value="{{ $marque }}">{{ $marque }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">2. Modèle</label>
                    <select name="modele" id="modele" class="form-control" required disabled style="font-size: 1.1rem; padding: 1rem;">
                        <option value="">Sélectionnez d'abord la marque</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">3. Motorisation (Optionnel)</label>
                    <select name="motorisation" id="motorisation" class="form-control" disabled style="font-size: 1.1rem; padding: 1rem;">
                        <option value="">Sélectionnez d'abord le modèle</option>
                    </select>
                </div>
            </div>

            <button type="submit" id="submit-btn" class="btn btn-primary" disabled style="width: 100%; font-size: 1.2rem; padding: 1rem;">
                🔍 Trouver les pièces compatibles
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const marqueSelect = document.getElementById('marque');
    const modeleSelect = document.getElementById('modele');
    const moteurSelect = document.getElementById('motorisation');
    const submitBtn = document.getElementById('submit-btn');

    marqueSelect.addEventListener('change', function() {
        const marque = this.value;
        modeleSelect.innerHTML = '<option value="">Chargement...</option>';
        modeleSelect.disabled = true;
        moteurSelect.innerHTML = '<option value="">Sélectionnez d\'abord le modèle</option>';
        moteurSelect.disabled = true;
        submitBtn.disabled = true;

        if(!marque) {
            modeleSelect.innerHTML = '<option value="">Sélectionnez d\'abord la marque</option>';
            return;
        }

        fetch(`{{ route('client.voiture.modeles') }}?marque=${encodeURIComponent(marque)}`)
            .then(res => res.json())
            .then(data => {
                modeleSelect.innerHTML = '<option value="">Sélectionnez le modèle</option>';
                data.forEach(modele => {
                    modeleSelect.innerHTML += `<option value="${modele}">${modele}</option>`;
                });
                modeleSelect.disabled = false;
            });
    });

    modeleSelect.addEventListener('change', function() {
        const marque = marqueSelect.value;
        const modele = this.value;
        
        if(!modele) {
            moteurSelect.innerHTML = '<option value="">Sélectionnez d\'abord le modèle</option>';
            moteurSelect.disabled = true;
            submitBtn.disabled = true;
            return;
        }

        submitBtn.disabled = false; 
        moteurSelect.innerHTML = '<option value="">Chargement...</option>';
        moteurSelect.disabled = true;

        fetch(`{{ route('client.voiture.motorisations') }}?marque=${encodeURIComponent(marque)}&modele=${encodeURIComponent(modele)}`)
            .then(res => res.json())
            .then(data => {
                moteurSelect.innerHTML = '<option value="">Toutes les motorisations</option>';
                data.forEach(moteur => {
                    moteurSelect.innerHTML += `<option value="${moteur}">${moteur}</option>`;
                });
                moteurSelect.disabled = false;
            });
    });
</script>
@endsection
