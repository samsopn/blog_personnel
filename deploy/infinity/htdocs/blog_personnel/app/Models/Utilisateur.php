<?php

namespace App\Models;

use App\Enums\RoleUtilisateur;
use Database\Factories\UtilisateurFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Utilisateur extends Authenticatable
{
    /** @use HasFactory<UtilisateurFactory> */
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => RoleUtilisateur::class,
        ];
    }

    public function estAdministrateur(): bool
    {
        return $this->role->estAdministrateur();
    }

    /**
     * URL publique de l'avatar (chemin relatif, compatible avec php artisan serve).
     */
    public function urlAvatar(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        return '/storage/'.str_replace('\\', '/', $this->avatar);
    }

    public function initialeAvatar(): string
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    public static function genererUsernameUnique(string $name, ?int $ignorerId = null): string
    {
        $base = Str::slug($name, '_');
        $base = preg_replace('/[^a-z0-9_]/', '', strtolower($base)) ?: 'utilisateur';
        $base = substr($base, 0, 30);

        if (! preg_match('/^[a-z]/', $base)) {
            $base = 'u_'.$base;
        }

        if (strlen($base) < 3) {
            $base = str_pad($base, 3, '0');
        }

        $pseudo = $base;
        $index = 2;

        while (static::query()
            ->when($ignorerId, fn ($query) => $query->whereKeyNot($ignorerId))
            ->where('username', $pseudo)
            ->exists()) {
            $suffixe = '_'.$index;
            $pseudo = substr($base, 0, 30 - strlen($suffixe)).$suffixe;
            $index++;
        }

        return $pseudo;
    }

    public static function normaliserUsername(string $username): string
    {
        return strtolower(trim($username));
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'user_id');
    }

    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class, 'user_id');
    }

    public function appreciations(): HasMany
    {
        return $this->hasMany(Appreciation::class, 'user_id');
    }

    public function favoris(): HasMany
    {
        return $this->hasMany(Favori::class, 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    protected static function newFactory(): UtilisateurFactory
    {
        return UtilisateurFactory::new();
    }
}
