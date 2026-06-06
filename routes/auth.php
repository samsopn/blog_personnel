<?php

use App\Http\Controllers\Auth\ConnexionController;
use App\Http\Controllers\Auth\DeconnexionController;
use App\Http\Controllers\Auth\InscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('inscription', [InscriptionController::class, 'create'])->name('inscription');
    Route::post('inscription', [InscriptionController::class, 'store'])->name('inscription.store');

    Route::get('connexion', [ConnexionController::class, 'create'])->name('connexion');
    Route::post('connexion', [ConnexionController::class, 'store'])->name('connexion.store');
});

Route::middleware('auth')->group(function () {
    Route::post('deconnexion', DeconnexionController::class)->name('deconnexion');
});
