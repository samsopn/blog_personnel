<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatutArticle;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\Etiquette;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        return view('admin.articles.index', [
            'articles' => Article::query()->with('categories')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.articles.create', [
            'categories' => Categorie::query()->orderBy('name')->get(),
        ]);
    }

    public function store(ArticleRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $article = new Article();
            $this->remplirArticle($article, $request);
            $article->save();

            $article->categories()->sync($request->validated('categories'));
            $this->synchroniserEtiquettes($article, $request->validated('tags', null));
        });

        return redirect()
            ->route('admin.articles.index')
            ->with('succes', 'Article créé avec succès.');
    }

    public function edit(Article $article): View
    {
        $article->load(['categories', 'etiquettes']);

        return view('admin.articles.edit', [
            'article' => $article,
            'categories' => Categorie::query()->orderBy('name')->get(),
            'tags' => $article->etiquettes->pluck('name')->implode(', '),
        ]);
    }

    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        DB::transaction(function () use ($request, $article): void {
            $this->remplirArticle($article, $request);
            $article->save();

            $article->categories()->sync($request->validated('categories'));
            $this->synchroniserEtiquettes($article, $request->validated('tags', null));
        });

        return redirect()
            ->route('admin.articles.index')
            ->with('succes', 'Article mis à jour avec succès.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('succes', 'Article supprimé avec succès.');
    }

    private function remplirArticle(Article $article, ArticleRequest $request): void
    {
        $titre = trim($request->validated('title'));
        $statut = StatutArticle::from($request->validated('status'));
        $publicationPlanifiee = $request->validated('published_at');

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }

            $article->image = $request->file('image')->store('articles', 'public');
        }

        $article->user_id = $request->user()->id;
        $article->title = $titre;
        $article->slug = $this->genererSlugUnique($titre, $article->exists ? $article : null);
        $article->content = $request->validated('content');
        $article->status = $statut;
        $article->published_at = $statut === StatutArticle::Publie
            ? (filled($publicationPlanifiee) ? Carbon::parse($publicationPlanifiee) : now())
            : null;
    }

    private function genererSlugUnique(string $titre, ?Article $article = null): string
    {
        $base = Str::slug($titre);
        $slug = $base;
        $index = 2;

        while ($this->slugExiste($slug, $article)) {
            $slug = $base.'-'.$index;
            $index++;
        }

        return $slug;
    }

    private function slugExiste(string $slug, ?Article $article = null): bool
    {
        $query = Article::query()->where('slug', $slug);

        if ($article) {
            $query->whereKeyNot($article->id);
        }

        return $query->exists();
    }

    private function synchroniserEtiquettes(Article $article, ?string $tagsBruts): void
    {
        $noms = collect(explode(',', (string) $tagsBruts))
            ->map(fn (string $nom): string => trim($nom))
            ->filter()
            ->unique()
            ->values();

        if ($noms->isEmpty()) {
            $article->etiquettes()->sync([]);

            return;
        }

        $ids = $noms
            ->map(fn (string $nom): Etiquette => Etiquette::firstOrCreate(
                ['slug' => Str::slug($nom)],
                ['name' => $nom]
            ))
            ->map(fn (Etiquette $etiquette): int => $etiquette->id)
            ->all();

        $article->etiquettes()->sync($ids);
    }
}
