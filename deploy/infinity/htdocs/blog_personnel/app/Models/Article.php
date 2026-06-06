<?php

namespace App\Models;

use App\Enums\StatutArticle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'image',
        'status',
        'views',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatutArticle::class,
            'published_at' => 'datetime',
            'views' => 'integer',
        ];
    }

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class, 'article_categorie', 'article_id', 'category_id');
    }

    public function etiquettes(): BelongsToMany
    {
        return $this->belongsToMany(Etiquette::class, 'article_etiquette', 'article_id', 'etiquette_id');
    }

    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class, 'article_id');
    }

    public function commentairesRacines(): HasMany
    {
        return $this->commentaires()->whereNull('parent_id');
    }

    public function appreciations(): HasMany
    {
        return $this->hasMany(Appreciation::class, 'article_id');
    }

    public function favoris(): HasMany
    {
        return $this->hasMany(Favori::class, 'article_id');
    }

    /**
     * URL publique de l'image (chemin relatif, compatible avec php artisan serve).
     */
    public function urlImage(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return '/storage/'.str_replace('\\', '/', $this->image);
    }

    public function scopePublies(Builder $query): Builder
    {
        return $query->where('status', StatutArticle::Publie);
    }

    public function scopeVisibles(Builder $query): Builder
    {
        return $query
            ->publies()
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
