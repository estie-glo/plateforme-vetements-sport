<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\ProfilController;

Route::get('/', fn () => redirect('/produits'));

// Catalogue (L2)
Route::get('/produits', [CatalogueController::class, 'index']);
Route::get('/produit/{id}', [CatalogueController::class, 'show'])->whereNumber('id');

// Comptes clients (L1)
Route::get('/inscription', [AuthController::class, 'formInscription'])->name('inscription');
Route::post('/inscription', [AuthController::class, 'inscription']);
Route::get('/connexion', [AuthController::class, 'formConnexion'])->name('login');
Route::post('/connexion', [AuthController::class, 'connexion']);
Route::post('/deconnexion', [AuthController::class, 'deconnexion'])->name('deconnexion');

Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil');
    Route::post('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/adresses', [ProfilController::class, 'adresseStore'])->name('adresse.store');

    // Panier (L3)
    Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
    Route::post('/panier/ajouter', [PanierController::class, 'ajouter'])->name('panier.ajouter');
    Route::post('/panier/ligne/{id}', [PanierController::class, 'modifier'])->name('panier.modifier');
    Route::post('/panier/ligne/{id}/supprimer', [PanierController::class, 'supprimer'])->name('panier.supprimer');

    // Commande (L3)
    Route::get('/commande/recapitulatif', [CommandeController::class, 'recap'])->name('commande.recap');
    Route::post('/commande', [CommandeController::class, 'valider'])->name('commande.valider');
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/{id}', [CommandeController::class, 'show'])->name('commandes.show');

    // Back-office administrateur (L6)
    Route::prefix('admin')->group(function () {
        Route::get('/produits', [AdminController::class, 'produits'])->name('admin.produits');
        Route::post('/produits', [AdminController::class, 'produitStore'])->name('admin.produit.store');
        Route::post('/produits/{id}/statut', [AdminController::class, 'produitStatut'])->name('admin.produit.statut');
        Route::post('/produits/{id}/variantes', [AdminController::class, 'varianteStore'])->name('admin.variante.store');
        Route::post('/variantes/{id}/stock', [AdminController::class, 'varianteStock'])->name('admin.variante.stock');
        Route::get('/commandes', [AdminController::class, 'commandes'])->name('admin.commandes');
        Route::post('/commandes/{id}/statut', [AdminController::class, 'commandeStatut'])->name('admin.commande.statut');
    });
});
