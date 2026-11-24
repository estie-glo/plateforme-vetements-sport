<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogueController;
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
});
