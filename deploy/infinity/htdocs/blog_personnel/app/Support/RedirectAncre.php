<?php

namespace App\Support;

use App\Models\Article;
use Illuminate\Http\RedirectResponse;

final class RedirectAncre
{
    public static function vers(string $url, string $fragment): RedirectResponse
    {
        return redirect(strtok($url, '#').'#'.$fragment);
    }

    public static function retour(string $fragment): RedirectResponse
    {
        return self::vers(url()->previous(), $fragment);
    }

    public static function article(Article $article, string $fragment): RedirectResponse
    {
        return redirect(route('articles.show', $article->slug).'#'.$fragment);
    }
}
