<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Favori;
use App\Support\RedirectAncre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriArticleController extends Controller
{
    public function toggle(Request $request, Article $article): JsonResponse|RedirectResponse
    {
        if (! Article::query()->visibles()->whereKey($article->id)->exists()) {
            abort(404);
        }

        $favori = Favori::query()
            ->where('article_id', $article->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($favori) {
            $favori->delete();
        } else {
            Favori::create([
                'article_id' => $article->id,
                'user_id' => auth()->id(),
            ]);
        }

        $article->loadCount('favoris');
        $active = ! $favori;

        if ($request->wantsJson()) {
            return response()->json([
                'active' => $active,
                'count' => $article->favoris_count,
            ]);
        }

        return RedirectAncre::retour('interactions');
    }
}
