<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\View\View;

class FavoriController extends Controller
{
    public function index(): View
    {
        $articles = Article::query()
            ->visibles()
            ->whereHas('favoris', fn ($query) => $query->where('user_id', auth()->id()))
            ->with(['categories', 'auteur'])
            ->withCount(['appreciations', 'favoris', 'commentaires'])
            ->latest('published_at')
            ->paginate(10);

        return view('user.favoris', [
            'articles' => $articles,
        ]);
    }
}
