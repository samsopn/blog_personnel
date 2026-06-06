@php
    $enfants = $commentairesParParent->get($commentaire->id, collect());
    $reponseOuverte = (string) old('parent_id') === (string) $commentaire->id;
    $aLike = (bool) ($commentaire->utilisateur_a_like ?? false);
    $estAuteur = auth()->id() === $commentaire->user_id;
@endphp

<article id="commentaire-{{ $commentaire->id }}" class="comment-node py-2" data-comment-id="{{ $commentaire->id }}">
    <div class="flex items-center gap-1.5">
        <x-avatar-utilisateur :utilisateur="$commentaire->auteur" />

        <header class="comment-meta flex min-w-0 flex-wrap items-center gap-1">
            <span class="comment-author text-[11px] font-bold leading-none text-slate-900" title="@{{ $commentaire->auteur->username }}">{{ $commentaire->auteur->name }}</span>
            <span class="text-[10px] leading-none text-slate-400" aria-hidden="true">·</span>
            <time class="comment-time text-[10px] leading-none text-slate-500" datetime="{{ $commentaire->created_at->toIso8601String() }}">
                {{ $commentaire->created_at->locale('fr')->diffForHumans() }}
            </time>
        </header>
    </div>

    <div class="mt-0.5 pl-6">
        <div class="comment-body text-xs leading-relaxed text-slate-800">{!! $commentaire->corpsFormate() !!}</div>

        @auth
            <div class="comment-reply-block mt-0.5">
                <input
                    type="checkbox"
                    id="reply-toggle-{{ $commentaire->id }}"
                    class="comment-reply-checkbox"
                    @checked($reponseOuverte)
                >

                <div class="comment-actions flex flex-wrap items-center gap-2">
                    <form
                        method="POST"
                        action="{{ route('articles.commentaires.appreciations.toggle', [$article, $commentaire]) }}"
                        class="comment-like-form inline-flex items-center gap-0.5"
                        data-interaction-toggle="comment-like"
                        data-active="{{ $aLike ? '1' : '0' }}"
                    >
                        @csrf
                        <x-interaction-pouce :active="$aLike" compact />
                        <span class="text-[10px] font-semibold text-slate-500" data-interaction-count>{{ $commentaire->appreciations_count }}</span>
                    </form>

                    <label for="reply-toggle-{{ $commentaire->id }}" class="comment-reply-link">Répondre</label>

                    @if ($estAuteur)
                        <form
                            method="POST"
                            action="{{ route('articles.commentaires.destroy', [$article, $commentaire]) }}"
                            class="inline"
                            onsubmit="return confirm('Supprimer ce commentaire ? Les réponses seront aussi supprimées.');"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="comment-delete-link">Supprimer</button>
                        </form>
                    @endif
                </div>

                <form
                    method="POST"
                    action="{{ route('articles.commentaires.store', $article) }}"
                    class="comment-reply-form"
                >
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $commentaire->id }}">
                    <label class="sr-only" for="reply-{{ $commentaire->id }}">Répondre à {{ $commentaire->auteur->name }}</label>
                    <textarea
                        id="reply-{{ $commentaire->id }}"
                        name="body"
                        rows="2"
                        required
                        class="comment-mention-textarea"
                        placeholder="Répondre…"
                    >{{ $reponseOuverte ? old('body') : '' }}</textarea>
                    <div class="comment-composer-actions">
                        <x-comment-envoyer label="Envoyer la réponse" />
                    </div>
                </form>
            </div>
        @else
            <div class="comment-actions mt-0.5 flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-0.5 text-slate-400" title="Likes">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75 2.25 2.25 0 012.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H9.494a2.25 2.25 0 01-2.244-2.077 4.502 4.502 0 00-1.423-.23H6.633z" />
                    </svg>
                    <span class="text-[10px] font-semibold">{{ $commentaire->appreciations_count }}</span>
                </span>
            </div>
        @endauth
    </div>

    @if ($enfants->isNotEmpty())
        <div class="comment-branch ml-6 mt-0.5 border-l-2 border-slate-300 pl-2.5">
            @foreach ($enfants as $enfant)
                @include('front._commentaire-item', [
                    'article' => $article,
                    'commentaire' => $enfant,
                    'commentairesParParent' => $commentairesParParent,
                    'profondeur' => $profondeur + 1,
                ])
            @endforeach
        </div>
    @endif
</article>
