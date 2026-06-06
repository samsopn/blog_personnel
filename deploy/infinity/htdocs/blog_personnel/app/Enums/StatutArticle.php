<?php

namespace App\Enums;

enum StatutArticle: string
{
    case Brouillon = 'draft';
    case Publie = 'published';

    public function estPublie(): bool
    {
        return $this === self::Publie;
    }
}
