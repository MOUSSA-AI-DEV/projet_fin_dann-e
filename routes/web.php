<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MarqueController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PieceController;
use App\Http\Controllers\Admin\ReferenceController;
use App\Http\Controllers\Admin\VoitureController;
use App\Http\Controllers\Admin\ReferenceVoitureController;
use App\Http\Controllers\Admin\CommandeController as AdminCommandeController;

use App\Http\Controllers\Client\AccueilController;
use App\Http\Controllers\Client\CatalogueController;
use App\Http\Controllers\Client\PanierController;
use App\Http\Controllers\Client\CommandeController;
use App\Http\Controllers\Client\FactureController;
use App\Http\Controllers\Client\CompteController;
use App\Http\Controllers\Client\RechercheVoitureController;

use Illuminate\Support\Facades\Route;

//public routes
Route::get('/', [AccueilController::class, 'index'])->name('client.accueil');
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('client.catalogue.index');

Route::get('/catalogue/{slug}', [CatalogueController::class, 'show'])->name('client.catalogue.show');
Route::get('/categorie/{slug}', [CatalogueController::class, 'parCategorie'])->name('client.categorie.show');
Route::get('/recherche', [CatalogueController::class, 'recherche'])->name('client.recherche');
Route::get('/api/search/suggestions', [CatalogueController::class, 'suggestions'])->name('client.search.suggestions');

Route::get('/recherche-voiture', [RechercheVoitureController::class, 'index'])->name('client.voiture.index');
Route::get('/api/voitures/modeles', [RechercheVoitureController::class, 'getModeles'])->name('client.voiture.modeles');
Route::get('/api/voitures/motorisations', [RechercheVoitureController::class, 'getMotorisations'])->name('client.voiture.motorisations');
Route::get('/recherche-voiture/resultats', [RechercheVoitureController::class, 'resultats'])->name('client.voiture.resultats');


Route::get('/panier', [PanierController::class, 'index'])->name('client.panier.index');
Route::post('/panier/ajouter', [PanierController::class, 'ajouter'])->name('client.panier.ajouter');
Route::put('/panier/modifier', [PanierController::class, 'modifier'])->name('client.panier.modifier');
Route::delete('/panier/supprimer/{id}', [PanierController::class, 'supprimer'])->name('client.panier.supprimer');
Route::get('/panier/compter', [PanierController::class, 'compter'])->name('client.panier.compter');

//guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

//authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //Client possibility
    Route::post('/commande/valider', [CommandeController::class, 'valider'])->name('client.commande.valider');
    Route::get('/commande/confirmation/{id}', [CommandeController::class, 'confirmation'])->name('client.commande.confirmation');
    Route::get('/mes-commandes', [CommandeController::class, 'mesCommandes'])->name('client.commande.liste');
    Route::get('/commande/{numero}', [CommandeController::class, 'detail'])->name('client.commande.detail');
    Route::get('/facture/{commande}/download', [FactureController::class, 'download'])->name('client.facture.download');

    Route::get('/mon-compte', [CompteController::class, 'profil'])->name('client.compte.profil');
    Route::put('/mon-compte', [CompteController::class, 'updateProfil'])->name('client.compte.update');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('users', AdminUserController::class);
            Route::patch('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
            
            Route::resource('marques', MarqueController::class);
            Route::resource('categories', CategoryController::class);
            Route::resource('pieces', PieceController::class);
            Route::resource('references', ReferenceController::class);
            Route::resource('voitures', VoitureController::class);
            Route::resource('commandes', AdminCommandeController::class)->except(['destroy', 'edit']);
            Route::get('commandes/{commande}/facture', [AdminCommandeController::class, 'showFacture'])->name('commandes.facture');
            Route::post('references/{reference}/voitures', [ReferenceVoitureController::class, 'attachVoiture'])->name('references.voitures.attach');
            Route::delete('references/{reference}/voitures/{voiture}', [ReferenceVoitureController::class, 'detachVoiture'])->name('references.voitures.detach');
        });
    });
});
