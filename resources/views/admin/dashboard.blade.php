<x-app-layout>
    @section('page-title', 'Tableau de Bord')

    <!-- Include Bootstrap CSS just for this view since the project uses Tailwind globally -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">
        <div class="row mb-4">
            <div class="col-12">
                <div class="p-4 rounded shadow-sm text-white" style="background: linear-gradient(90deg, #0d6efd 0%, #198754 50%, #dc3545 100%);">
                    <h2 class="fw-bolder mb-1">Tableau de Bord Administrateur</h2>
                    <p class="mb-0 text-white-50 fw-medium">Aperçu rapide et coloré des statistiques de la boutique</p>
                </div>
            </div>
        </div>

        <!-- Statistiques des commandes du jour -->
        <h3 class="h5 fw-bold text-dark mb-4 border-bottom border-primary border-2 pb-2 d-inline-block">Commandes du jour</h3>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 border-top border-primary border-4 rounded-3 h-100 transition-shadow">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ms-4">
                            <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">En attente</p>
                            <h2 class="text-primary fw-black mb-0 display-6 fw-bold">{{ $commandesPendingToday }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 border-top border-success border-4 rounded-3 transition-shadow">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="ms-4">
                            <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Payées</p>
                            <h2 class="text-success mb-0 display-6 fw-bold">{{ $commandesPaidToday }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 border-top border-danger border-4 rounded-3 transition-shadow">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <div class="ms-4">
                            <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Échouées / Remboursées</p>
                            <h2 class="text-danger mb-0 display-6 fw-bold">{{ $commandesFailedToday }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chiffre d'affaires & Utilisateurs -->
        <h3 class="h5 fw-bold text-dark mb-4 border-bottom border-success border-2 pb-2 d-inline-block">Aperçu global</h3>
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #0d6efd !important;">
                    <div class="card-body p-4">
                        <p class="text-uppercase text-primary fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">C.A. (24h)</p>
                        <h4 class="text-dark fw-bold mb-0">{{ number_format($ca24h, 2, ',', ' ') }} €</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #198754 !important;">
                    <div class="card-body p-4">
                        <p class="text-uppercase text-success fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">C.A. (7 jours)</p>
                        <h4 class="text-dark fw-bold mb-0">{{ number_format($ca7j, 2, ',', ' ') }} €</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="border-left: 4px solid #dc3545 !important;">
                    <div class="card-body p-4">
                        <p class="text-uppercase text-danger fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">C.A. (30 jours)</p>
                        <h4 class="text-dark fw-bold mb-0">{{ number_format($ca30j, 2, ',', ' ') }} €</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border border-primary border-opacity-25 rounded-3 h-100" style="background: linear-gradient(135deg, #f0f8ff 0%, #ffffff 100%);">
                    <div class="card-body p-4">
                        <p class="text-uppercase text-primary fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">Nvx Utilisateurs (30j)</p>
                        <div class="d-flex align-items-baseline">
                            <h3 class="text-primary fw-bold mb-0 me-2">{{ $nouveauxUtilisateurs }}</h3>
                            <span class="text-primary text-opacity-75 fw-medium small">inscrits</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Liste du stock critique -->
            <div class="col-lg-6">
                <div class="card shadow-sm border border-danger border-opacity-25 rounded-3 overflow-hidden h-100">
                    <div class="card-header bg-danger text-white py-3 px-4 d-flex justify-content-between align-items-center border-0" style="background: linear-gradient(90deg, #dc3545 0%, #b02a37 100%);">
                        <h5 class="mb-0 fw-bold d-flex align-items-center fs-6">
                            <svg width="20" height="20" class="me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Stock Critique (< 50)
                        </h5>
                        <span class="badge bg-white text-danger rounded-pill shadow-sm px-3 py-2">{{ $countStockCritique }} pièces</span>
                    </div>
                    <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                        @if($countStockCritique > 0)
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #fdf2f2; position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th class="text-danger border-bottom border-danger border-opacity-25 py-3 px-4 text-uppercase fw-bold" style="font-size: 0.75rem;">ID</th>
                                        <th class="text-danger border-bottom border-danger border-opacity-25 py-3 px-4 text-uppercase fw-bold" style="font-size: 0.75rem;">Nom de la pièce</th>
                                        <th class="text-danger border-bottom border-danger border-opacity-25 py-3 px-4 text-uppercase fw-bold text-end" style="font-size: 0.75rem;">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($piecesStockCritique as $piece)
                                        <tr>
                                            <td class="text-danger text-opacity-75 fw-medium py-3 px-4 align-middle">#{{ $piece->id }}</td>
                                            <td class="text-dark fw-bold py-3 px-4 align-middle">{{ $piece->nom }}</td>
                                            <td class="text-end py-3 px-4 align-middle">
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 fs-6">{{ $piece->stock }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-5 text-center text-muted d-flex flex-column align-items-center justify-content-center h-100">
                                <svg width="48" height="48" class="text-success mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <p class="mb-0 fw-medium">Aucune pièce en stock critique pour le moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Top 10 Séries/Références vendues -->
            <div class="col-lg-6">
                <div class="card shadow-sm border border-primary border-opacity-25 rounded-3 overflow-hidden h-100">
                    <div class="card-header bg-primary text-white py-3 px-4 border-0" style="background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);">
                        <h5 class="mb-0 fw-bold d-flex align-items-center fs-6">
                            <svg width="20" height="20" class="me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            Top 10 Séries Vendues
                        </h5>
                    </div>
                    <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                        @if(count($topSeries) > 0)
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #f0f8ff; position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th class="text-primary border-bottom border-primary border-opacity-25 py-3 px-4 text-uppercase fw-bold" style="font-size: 0.75rem;">Rang</th>
                                        <th class="text-primary border-bottom border-primary border-opacity-25 py-3 px-4 text-uppercase fw-bold" style="font-size: 0.75rem;">Réf / Modèle</th>
                                        <th class="text-primary border-bottom border-primary border-opacity-25 py-3 px-4 text-uppercase fw-bold text-end" style="font-size: 0.75rem;">Ventes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topSeries as $index => $serie)
                                        <tr>
                                            <td class="py-3 px-4 align-middle">
                                                <span class="badge rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                                    {{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 align-middle">
                                                <p class="text-dark fw-bold mb-0">{{ $serie->nom ?: $serie->reference }}</p>
                                                <p class="text-primary text-opacity-75 small fw-medium mb-0">{{ $serie->reference }}</p>
                                            </td>
                                            <td class="text-end py-3 px-4 align-middle">
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fs-6">
                                                    {{ $serie->total_vendu }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-5 text-center text-muted d-flex align-items-center justify-content-center h-100">
                                <p class="mb-0 fw-medium">Aucune vente enregistrée pour le moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
