<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Commentaire;
use App\Models\CommentaireAppreciation;
use App\Support\RedirectAncre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentaireAppreciationController extends Controller
{
    public function toggle(Request $request, Article $article, Commentaire $commentaire): JsonResponse|RedirectResponse
    {
        if (! Article::query()->visibles()->whereKey($article->id)->exists()) {
            abort(404);
        }

        abort_unless($commentaire->article_id === $article->id, 404);

        $appreciation = CommentaireAppreciation::query()
            ->where('commentaire_id', $commentaire->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($appreciation) {
            $appreciation->delete();
        } else {
            CommentaireAppreciation::create([
                'commentaire_id' => $commentaire->id,
                'user_id' => auth()->id(),
            ]);
        }

        $commentaire->loadCount('appreciations');
        $active = ! $appreciation;

        if ($request->wantsJson()) {
            return response()->json([
                'active' => $active,
                'count' => $commentaire->appreciations_count,
            ]);
        }

        return RedirectAncre::article($article, 'commentaire-'.$commentaire->id);
    }
}
