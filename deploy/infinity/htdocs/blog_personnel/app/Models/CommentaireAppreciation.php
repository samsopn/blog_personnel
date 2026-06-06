<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentaireAppreciation extends Model
{
    protected $table = 'commentaire_appreciations';

    protected $fillable = [
        'commentaire_id',
        'user_id',
    ];

    public function commentaire(): BelongsTo
    {
        return $this->belongsTo(Commentaire::class, 'commentaire_id');
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
    }
}
