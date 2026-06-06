<?php

namespace App\Services;

use App\Models\Utilisateur;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CommentaireMentionService
{
    public const PATTERN = '/@([a-zA-Z][a-zA-Z0-9_]{2,29})\b/';

    /**
     * @return list<string>
     */
    public function extrairePseudos(string $texte): array
    {
        preg_match_all(self::PATTERN, $texte, $correspondances);

        return collect($correspondances[1] ?? [])
            ->map(fn (string $pseudo): string => strtolower($pseudo))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param  list<string>  $pseudos
     * @return Collection<string, Utilisateur>
     */
    public function utilisateursParPseudo(array $pseudos): Collection
    {
        if ($pseudos === []) {
            return collect();
        }

        return Utilisateur::query()
            ->whereIn('username', $pseudos)
            ->get()
            ->keyBy(fn (Utilisateur $utilisateur): string => $utilisateur->username);
    }

    public function formater(string $texte): string
    {
        $parties = preg_split(self::PATTERN, $texte, -1, PREG_SPLIT_DELIM_CAPTURE);

        if ($parties === false) {
            return e($texte);
        }

        $pseudos = [];
        foreach ($parties as $index => $partie) {
            if ($index % 2 === 1) {
                $pseudos[] = strtolower($partie);
            }
        }

        $utilisateurs = $this->utilisateursParPseudo($pseudos);
        $html = '';

        foreach ($parties as $index => $partie) {
            if ($index % 2 === 1) {
                $pseudo = strtolower($partie);
                $utilisateur = $utilisateurs->get($pseudo);

                if ($utilisateur) {
                    $html .= '<span class="comment-mention" title="'.e($utilisateur->name).'">@'.e($utilisateur->username).'</span>';
                } else {
                    $html .= e('@'.$partie);
                }

                continue;
            }

            $html .= nl2br(e($partie));
        }

        return $html;
    }
}
