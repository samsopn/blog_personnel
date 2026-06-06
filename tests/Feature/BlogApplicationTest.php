<?php

namespace Tests\Feature;

use App\Enums\StatutArticle;
use App\Models\Appreciation;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\Commentaire;
use App\Models\CommentaireAppreciation;
use App\Models\Favori;
use App\Models\Notification;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BlogApplicationTest extends TestCase
{
    use RefreshDatabase;

    private function creerArticlePublie(?Utilisateur $auteur = null, array $attributs = []): Article
    {
        $auteur ??= Utilisateur::factory()->create();

        return Article::create(array_merge([
            'user_id' => $auteur->id,
            'title' => 'Article de test',
            'slug' => 'article-de-test-'.Str::random(6),
            'content' => 'Contenu de l\'article de test.',
            'status' => StatutArticle::Publie,
            'published_at' => now(),
            'views' => 0,
        ], $attributs));
    }

    public function test_pages_publiques_accessibles(): void
    {
        $article = $this->creerArticlePublie();
        $categorie = Categorie::create(['name' => 'PHP', 'slug' => 'php']);
        $article->categories()->attach($categorie->id);

        $this->get(route('accueil'))->assertOk();
        $this->get(route('articles.show', $article->slug))->assertOk();
        $this->get(route('categories.show', 'php'))->assertOk();
        $this->get(route('recherche', ['q' => 'test']))->assertOk();
    }

    public function test_article_brouillon_invisible_sur_le_front(): void
    {
        $article = $this->creerArticlePublie(null, [
            'status' => StatutArticle::Brouillon,
            'published_at' => null,
        ]);

        $this->get(route('articles.show', $article->slug))->assertNotFound();
    }

    public function test_inscription_et_connexion(): void
    {
        $this->get(route('auth.inscription'))->assertOk();

        $this->post(route('auth.inscription.store'), [
            'name' => 'Jean Dupont',
            'username' => 'jeandupont',
            'email' => 'jean@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('accueil'));

        $this->assertAuthenticated();
        $this->post(route('auth.deconnexion'))->assertRedirect(route('accueil'));
        $this->assertGuest();

        $this->post(route('auth.connexion.store'), [
            'email' => 'jean@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('accueil'));

        $this->assertAuthenticated();
    }

    public function test_like_et_favori_article_en_json(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $article = $this->creerArticlePublie();

        $this->actingAs($utilisateur)
            ->postJson(route('articles.appreciations.toggle', $article))
            ->assertOk()
            ->assertJson(['active' => true, 'count' => 1]);

        $this->assertDatabaseHas('appreciations', [
            'article_id' => $article->id,
            'user_id' => $utilisateur->id,
        ]);

        $this->actingAs($utilisateur)
            ->postJson(route('articles.appreciations.toggle', $article))
            ->assertOk()
            ->assertJson(['active' => false, 'count' => 0]);

        $this->actingAs($utilisateur)
            ->postJson(route('articles.favoris.toggle', $article))
            ->assertOk()
            ->assertJson(['active' => true, 'count' => 1]);

        $this->assertDatabaseHas('favoris', [
            'article_id' => $article->id,
            'user_id' => $utilisateur->id,
        ]);
    }

    public function test_commentaire_creation_suppression_et_like(): void
    {
        $auteur = Utilisateur::factory()->create();
        $lecteur = Utilisateur::factory()->create();
        $article = $this->creerArticlePublie($auteur);

        $this->actingAs($lecteur)
            ->post(route('articles.commentaires.store', $article), [
                'body' => 'Super article !',
            ])
            ->assertRedirect(route('articles.show', $article->slug).'#commentaire-1');

        $commentaire = Commentaire::query()->first();
        $this->assertNotNull($commentaire);
        $this->assertSame('Super article !', $commentaire->body);

        $this->actingAs($auteur)
            ->postJson(route('articles.commentaires.appreciations.toggle', [$article, $commentaire]))
            ->assertOk()
            ->assertJson(['active' => true, 'count' => 1]);

        $this->actingAs($lecteur)
            ->delete(route('articles.commentaires.destroy', [$article, $commentaire]))
            ->assertRedirect(route('articles.show', $article->slug).'#commentaires');

        $this->assertDatabaseMissing('commentaires', ['id' => $commentaire->id]);
    }

    public function test_suppression_commentaire_refusee_pour_un_autre_utilisateur(): void
    {
        $auteur = Utilisateur::factory()->create();
        $intrus = Utilisateur::factory()->create();
        $article = $this->creerArticlePublie($auteur);

        $commentaire = Commentaire::create([
            'article_id' => $article->id,
            'user_id' => $auteur->id,
            'body' => 'Mon commentaire',
        ]);

        $this->actingAs($intrus)
            ->delete(route('articles.commentaires.destroy', [$article, $commentaire]))
            ->assertForbidden();

        $this->assertDatabaseHas('commentaires', ['id' => $commentaire->id]);
    }

    public function test_mention_cree_une_notification(): void
    {
        $alice = Utilisateur::factory()->create(['username' => 'alice', 'name' => 'Alice']);
        $bob = Utilisateur::factory()->create(['username' => 'bob', 'name' => 'Bob']);
        $article = $this->creerArticlePublie($alice);

        $this->actingAs($bob)
            ->post(route('articles.commentaires.store', $article), [
                'body' => 'Salut @alice !',
            ])
            ->assertRedirect();

        $commentaire = Commentaire::query()->first();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $alice->id,
            'mentionne_par_id' => $bob->id,
            'commentaire_id' => $commentaire->id,
        ]);

        $this->actingAs($alice)
            ->get(route('user.notifications'))
            ->assertOk()
            ->assertSee('Bob', false)
            ->assertSee($article->title, false);
    }

    public function test_autocomplete_mentions(): void
    {
        Utilisateur::factory()->create(['username' => 'samson', 'name' => 'Samson']);
        $utilisateur = Utilisateur::factory()->create();

        $this->actingAs($utilisateur)
            ->getJson(route('mentions.utilisateurs', ['q' => 'sam']))
            ->assertOk()
            ->assertJsonFragment(['username' => 'samson']);
    }

    public function test_page_favoris_utilisateur(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $article = $this->creerArticlePublie();

        Favori::create([
            'article_id' => $article->id,
            'user_id' => $utilisateur->id,
        ]);

        $this->actingAs($utilisateur)
            ->get(route('user.favoris'))
            ->assertOk()
            ->assertSee($article->title);
    }

    public function test_profil_utilisateur(): void
    {
        $utilisateur = Utilisateur::factory()->create(['username' => 'monpseudo']);

        $this->actingAs($utilisateur)
            ->get(route('user.profil'))
            ->assertOk()
            ->assertSee('monpseudo');

        $this->actingAs($utilisateur)
            ->put(route('user.profil.update'), [
                'username' => 'nouveaupseudo',
                'name' => 'Nouveau Nom',
            ])
            ->assertRedirect(route('user.profil'));

        $this->assertSame('nouveaupseudo', $utilisateur->fresh()->username);
    }

    public function test_admin_dashboard_protege(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $admin = Utilisateur::factory()->administrateur()->create();

        $this->actingAs($utilisateur)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_middleware_auth_sur_actions_protegees(): void
    {
        $article = $this->creerArticlePublie();

        $this->post(route('articles.commentaires.store', $article), ['body' => 'test'])
            ->assertRedirect(route('auth.connexion'));

        $this->post(route('articles.appreciations.toggle', $article))
            ->assertRedirect(route('auth.connexion'));
    }

    public function test_like_commentaire_sans_js_fallback_redirige_avec_ancre(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $article = $this->creerArticlePublie();
        $commentaire = Commentaire::create([
            'article_id' => $article->id,
            'user_id' => $utilisateur->id,
            'body' => 'Commentaire',
        ]);

        $this->actingAs($utilisateur)
            ->post(route('articles.commentaires.appreciations.toggle', [$article, $commentaire]))
            ->assertRedirect(route('articles.show', $article->slug).'#commentaire-'.$commentaire->id);

        $this->assertDatabaseHas('commentaire_appreciations', [
            'commentaire_id' => $commentaire->id,
            'user_id' => $utilisateur->id,
        ]);
    }
}
