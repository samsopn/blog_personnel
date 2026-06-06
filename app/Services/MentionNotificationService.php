<?php

namespace App\Services;

use App\Models\Commentaire;
use App\Models\Notification;
use App\Models\Utilisateur;
use Illuminate\Support\Collection;

class MentionNotificationService
{
    public function __construct(
        private readonly CommentaireMentionService $mentions,
    ) {}

    /**
     * @return Collection<int, Notification>
     */
    public function notifierMentions(Commentaire $commentaire): Collection
    {
        $pseudos = $this->mentions->extrairePseudos($commentaire->body);
        $utilisateurs = $this->mentions->utilisateursParPseudo($pseudos);

        $notifications = collect();

        foreach ($utilisateurs as $utilisateur) {
            if ($utilisateur->id === $commentaire->user_id) {
                continue;
            }

            $notification = Notification::query()->firstOrCreate(
                [
                    'commentaire_id' => $commentaire->id,
                    'user_id' => $utilisateur->id,
                ],
                [
                    'mentionne_par_id' => $commentaire->user_id,
                    'article_id' => $commentaire->article_id,
                ]
            );

            $notifications->push($notification);
        }

        return $notifications;
    }

    public function compterNonLues(Utilisateur $utilisateur): int
    {
        return Notification::query()
            ->where('user_id', $utilisateur->id)
            ->whereNull('read_at')
            ->count();
    }
}
