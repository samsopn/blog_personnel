@php
    $commentairesParParent = $article->commentaires->groupBy(fn ($commentaire) => $commentaire->parent_id);
    $commentairesRacines = $commentairesParParent->get(null, collect())->sortByDesc('created_at');
@endphp

<section
    id="commentaires"
    class="comment-section"
    data-commentaires
    data-mentions-url="{{ route('mentions.utilisateurs') }}"
>
    <h2 class="comment-section-title">
        Commentaires ({{ $article->commentaires_count }})
    </h2>

    @auth
        <form method="POST" action="{{ route('articles.commentaires.store', $article) }}" class="comment-composer">
            @csrf
            <textarea
                id="body"
                name="body"
                rows="3"
                required
                class="comment-mention-textarea"
                placeholder="Ajouter un commentaire…"
            >{{ old('parent_id') ? '' : old('body') }}</textarea>
            <div class="comment-composer-actions">
                <x-comment-envoyer />
            </div>
            @error('body')
                <p class="comment-error">{{ $message }}</p>
            @enderror
        </form>
    @else
        <p class="comment-guest-hint">
            <a href="{{ route('auth.connexion') }}">Connectez-vous</a> pour commenter.
        </p>
    @endauth

    <div class="comment-list">
        @forelse ($commentairesRacines as $commentaire)
            @include('front._commentaire-item', [
                'article' => $article,
                'commentaire' => $commentaire,
                'commentairesParParent' => $commentairesParParent,
                'profondeur' => 0,
            ])
        @empty
            <p class="comment-empty">Aucun commentaire pour le moment.</p>
        @endforelse
    </div>
</section>
