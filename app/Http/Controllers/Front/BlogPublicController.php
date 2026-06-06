<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class BlogPublicController extends Controller
{
    public function accueil(): View
    {
        return view('front.accueil', [
            'articles' => $this->requeteArticlesBase()
                ->latest('published_at')
                ->latest('id')
                ->paginate(10),
            'articlesPopulaires' => $this->requeteArticlesBase()
                ->orderByDesc('views')
                ->take(5)
                ->get(),
            'categories' => $this->categoriesAvecCompteur(),
        ]);
    }

    public function article(string $slug): View
    {
        $article = $this->requeteArticlesBase()
            ->where('slug', $slug)
            ->firstOrFail();

        if ($this->doitCompterUneVue($article)) {
            $article->increment('views');
            $article->refresh();
        }

        $utilisateur = auth()->user();

        $article->load([
            'commentaires' => function ($query) use ($utilisateur): void {
                $query->with('auteur')->withCount('appreciations')->oldest();

                if ($utilisateur) {
                    $query->withExists([
                        'appreciations as utilisateur_a_like' => fn ($q) => $q->where('user_id', $utilisateur->id),
                    ]);
                }
            },
        ])->loadCount(['appreciations', 'favoris', 'commentaires']);

        return view('front.article', [
            'article' => $article,
            'articlesPopulaires' => $this->requeteArticlesBase()
                ->whereKeyNot($article->id)
                ->orderByDesc('views')
                ->take(5)
                ->get(),
            'aLike' => $utilisateur && $article->appreciations()->where('user_id', $utilisateur->id)->exists(),
            'aFavori' => $utilisateur && $article->favoris()->where('user_id', $utilisateur->id)->exists(),
        ]);
    }

    public function categorie(string $slug): View
    {
        $categorie = Categorie::query()->where('slug', $slug)->firstOrFail();

        $articles = $this->requeteArticlesBase()
            ->whereHas('categories', fn (Builder $query): Builder => $query->whereKey($categorie->id))
            ->latest('published_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('front.categorie', [
            'categorie' => $categorie,
            'articles' => $articles,
            'categories' => $this->categoriesAvecCompteur(),
        ]);
    }

    public function recherche(Request $request): View
    {
        $terme = trim((string) $request->string('q', ''));
        $slugCategorie = (string) $request->string('categorie', '');

        $articlesQuery = $this->requeteArticlesBase();

        if ($terme !== '') {
            $articlesQuery->where(function (Builder $query) use ($terme): void {
                $query
                    ->where('title', 'like', '%'.$terme.'%')
                    ->orWhere('content', 'like', '%'.$terme.'%');
            });
        }

        if ($slugCategorie !== '') {
            $articlesQuery->whereHas('categories', fn (Builder $query): Builder => $query->where('slug', $slugCategorie));
        }

        return view('front.recherche', [
            'articles' => $articlesQuery
                ->latest('published_at')
                ->latest('id')
                ->paginate(10)
                ->appends($request->query()),
            'categories' => $this->categoriesAvecCompteur(),
            'terme' => $terme,
            'slugCategorie' => $slugCategorie,
        ]);
    }

    private function requeteArticlesBase(): Builder
    {
        return Article::query()
            ->visibles()
            ->with(['auteur', 'categories', 'etiquettes'])
            ->withCount(['appreciations', 'favoris', 'commentaires']);
    }

    private function categoriesAvecCompteur()
    {
        return Categorie::query()
            ->whereHas('articles', fn (Builder $query): Builder => $query->visibles())
            ->withCount(['articles as articles_publies_count' => fn (Builder $query): Builder => $query->visibles()])
            ->orderBy('name')
            ->get();
    }

    private function doitCompterUneVue(Article $article): bool
    {
        $fingerprint = auth()->check()
            ? 'user:'.auth()->id()
            : 'guest:'.hash('sha256', request()->ip().'|'.request()->userAgent());

        $cle = "article_viewed:{$article->id}:{$fingerprint}";

        if (Cache::has($cle)) {
            return false;
        }

        Cache::put($cle, true, now()->addMinutes(30));

        return true;
    }
}
