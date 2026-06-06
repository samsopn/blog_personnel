<?php

namespace Database\Factories;

use App\Enums\RoleUtilisateur;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Utilisateur>
 */
class UtilisateurFactory extends Factory
{
    protected static ?string $password;

    protected $model = Utilisateur::class;

    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'username' => Utilisateur::genererUsernameUnique($name),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => RoleUtilisateur::Utilisateur,
            'avatar' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function administrateur(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => RoleUtilisateur::Administrateur,
        ]);
    }
}
