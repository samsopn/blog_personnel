<?php

namespace Database\Seeders;

use App\Enums\StatutArticle;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MetierSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Utilisateur::factory()->administrateur()->create([
            'name' => 'Samson',
            'email' => 'admin@gmail.com',
            'password' => 'password',
        ]);

        $categories = collect([
            'Laravel',
            'PHP',
            'Linux',
        ])->map(fn (string $nom) => Categorie::create([
            'name' => $nom,
            'slug' => Str::slug($nom),
        ]));

        $articles = [
            [
                'title' => 'Bienvenue sur mon blog technique',
                'content' => 'Premier article de démonstration pour valider la structure du projet.',
                'status' => StatutArticle::Publie,
            ],
            [
                'title' => 'Introduction à Laravel',
                'content' => 'Découverte du framework Laravel et de son écosystème.',
                'status' => StatutArticle::Publie,
            ],
            [
                'title' => 'Article en brouillon',
                'content' => 'Cet article ne doit pas apparaître sur le front public.',
                'status' => StatutArticle::Brouillon,
            ],
        ];

        foreach ($articles as $index => $donnees) {
            $article = Article::create([
                'user_id' => $admin->id,
                'title' => $donnees['title'],
                'slug' => Str::slug($donnees['title']),
                'content' => $donnees['content'],
                'status' => $donnees['status'],
                'views' => $index * 10,
                'published_at' => $donnees['status'] === StatutArticle::Publie ? now() : null,
            ]);

            $article->categories()->attach($categories[$index % $categories->count()]->id);
        }
    }
}
