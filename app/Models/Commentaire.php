<?php

namespace App\Models;

use App\Services\CommentaireMentionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commentaire extends Model
{
    protected $table = 'commentaires';

    protected $fillable = [
        'article_id',
        'user_id',
        'parent_id',
        'body',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function reponses(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function appreciations(): HasMany
    {
        return $this->hasMany(CommentaireAppreciation::class, 'commentaire_id');
    }

    public function estRacine(): bool
    {
        return $this->parent_id === null;
    }

    public function corpsFormate(): string
    {
        return app(CommentaireMentionService::class)->formater($this->body);
    }
}
