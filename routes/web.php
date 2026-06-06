<?php

use App\Http\Controllers\Front\AppreciationController;
use App\Http\Controllers\Front\BlogPublicController;
use App\Http\Controllers\Front\CommentaireAppreciationController;
use App\Http\Controllers\Front\CommentaireController;
use App\Http\Controllers\Front\FavoriArticleController;
use App\Http\Controllers\Front\MentionUtilisateurController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogPublicController::class, 'accueil'])->name('accueil');
Route::get('/articles/{slug}', [BlogPublicController::class, 'article'])->name('articles.show');
Route::get('/categories/{slug}', [BlogPublicController::class, 'categorie'])->name('categories.show');
Route::get('/recherche', [BlogPublicController::class, 'recherche'])->name('recherche');

Route::middleware('auth')->group(function () {
    Route::get('/mentions/utilisateurs', MentionUtilisateurController::class)->name('mentions.utilisateurs');
    Route::post('/articles/{article}/commentaires', [CommentaireController::class, 'store'])->name('articles.commentaires.store');
    Route::delete('/articles/{article}/commentaires/{commentaire}', [CommentaireController::class, 'destroy'])->name('articles.commentaires.destroy');
    Route::post('/articles/{article}/commentaires/{commentaire}/appreciations', [CommentaireAppreciationController::class, 'toggle'])->name('articles.commentaires.appreciations.toggle');
    Route::post('/articles/{article}/appreciations', [AppreciationController::class, 'toggle'])->name('articles.appreciations.toggle');
    Route::post('/articles/{article}/favoris', [FavoriArticleController::class, 'toggle'])->name('articles.favoris.toggle');
});
