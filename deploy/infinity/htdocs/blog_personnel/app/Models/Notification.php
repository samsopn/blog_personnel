<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'mentionne_par_id',
        'article_id',
        'commentaire_id',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function destinataire(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
    }

    public function mentionnePar(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'mentionne_par_id');
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function commentaire(): BelongsTo
    {
        return $this->belongsTo(Commentaire::class, 'commentaire_id');
    }

    public function estLue(): bool
    {
        return $this->read_at !== null;
    }

    public function marquerCommeLue(): void
    {
        if ($this->estLue()) {
            return;
        }

        $this->forceFill(['read_at' => now()])->save();
    }

    public function url(): string
    {
        $this->loadMissing('article');

        return route('articles.show', $this->article->slug).'#commentaire-'.$this->commentaire_id;
    }
}
