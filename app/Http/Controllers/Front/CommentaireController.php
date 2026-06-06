<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CommentaireRequest;
use App\Models\Article;
use App\Models\Commentaire;
use App\Services\MentionNotificationService;
use App\Support\RedirectAncre;
use Illuminate\Http\RedirectResponse;

class CommentaireController extends Controller
{
    public function __construct(
        private readonly MentionNotificationService $mentionNotifications,
    ) {}

    public function store(CommentaireRequest $request, Article $article): RedirectResponse
    {
        if (! Article::query()->visibles()->whereKey($article->id)->exists()) {
            abort(404);
        }

        $parentId = $request->validated('parent_id');

        if ($parentId !== null) {
            $parent = Commentaire::query()->findOrFail($parentId);

            if ($parent->article_id !== $article->id) {
                return RedirectAncre::article($article, 'commentaire-'.$parentId)
                    ->withErrors([
                        'body' => 'Ce commentaire n\'appartient pas à cet article.',
                    ]);
            }
        }

        $commentaire = Commentaire::create([
            'article_id' => $article->id,
            'user_id' => $request->user()->id,
            'parent_id' => $parentId,
            'body' => trim($request->validated('body')),
        ]);

        $this->mentionNotifications->notifierMentions($commentaire);

        return RedirectAncre::article($article, 'commentaire-'.$commentaire->id);
    }

    public function destroy(Article $article, Commentaire $commentaire): RedirectResponse
    {
        if (! Article::query()->visibles()->whereKey($article->id)->exists()) {
            abort(404);
        }

        abort_unless($commentaire->article_id === $article->id, 404);
        abort_unless($commentaire->user_id === auth()->id(), 403);

        $fragment = $commentaire->parent_id
            ? 'commentaire-'.$commentaire->parent_id
            : 'commentaires';

        $commentaire->delete();

        return RedirectAncre::article($article, $fragment);
    }
}
