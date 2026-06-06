<?php

use App\Http\Controllers\Auth\ConnexionAdminController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategorieController;
use App\Http\Controllers\Admin\CommentaireController;
use App\Models\Article;
use App\Models\Commentaire;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('connexion', [ConnexionAdminController::class, 'create'])->name('connexion');
    Route::post('connexion', [ConnexionAdminController::class, 'store'])->name('connexion.store');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard', [
            'utilisateur' => auth()->user(),
            'derniersArticles' => Article::query()->latest()->take(5)->get(),
            'derniersCommentaires' => Commentaire::query()->with(['auteur', 'article'])->latest()->take(5)->get(),
        ]);
    })->name('dashboard');

    Route::resource('categories', CategorieController::class)->except(['show']);
    Route::resource('articles', ArticleController::class)->except(['show']);
    Route::get('commentaires', [CommentaireController::class, 'index'])->name('commentaires.index');
    Route::delete('commentaires/{commentaire}', [CommentaireController::class, 'destroy'])->name('commentaires.destroy');
});
