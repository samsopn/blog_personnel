<?php

use App\Http\Controllers\User\FavoriController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\ProfilController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('profil', [ProfilController::class, 'edit'])->name('profil');
    Route::put('profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('favoris', [FavoriController::class, 'index'])->name('favoris');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('notifications/tout-lu', [NotificationController::class, 'toutMarquerCommeLu'])->name('notifications.tout-lu');
    Route::post('notifications/{notification}/lu', [NotificationController::class, 'marquerCommeLue'])->name('notifications.lu');
});
