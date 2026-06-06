<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Appreciation;
use App\Models\Article;
use App\Support\RedirectAncre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppreciationController extends Controller
{
    public function toggle(Request $request, Article $article): JsonResponse|RedirectResponse
    {
        if (! Article::query()->visibles()->whereKey($article->id)->exists()) {
            abort(404);
        }

        $appreciation = Appreciation::query()
            ->where('article_id', $article->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($appreciation) {
            $appreciation->delete();
        } else {
            Appreciation::create([
                'article_id' => $article->id,
                'user_id' => auth()->id(),
            ]);
        }

        $article->loadCount('appreciations');
        $active = ! $appreciation;

        if ($request->wantsJson()) {
            return response()->json([
                'active' => $active,
                'count' => $article->appreciations_count,
            ]);
        }

        return RedirectAncre::retour('interactions');
    }
}
