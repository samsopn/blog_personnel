<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategorieRequest;
use App\Models\Categorie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategorieController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Categorie::query()->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(CategorieRequest $request): RedirectResponse
    {
        $nom = trim($request->validated('name'));

        Categorie::create([
            'name' => $nom,
            'slug' => Str::slug($nom),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('succes', 'Catégorie créée avec succès.');
    }

    public function edit(Categorie $category): View
    {
        return view('admin.categories.edit', [
            'categorie' => $category,
        ]);
    }

    public function update(CategorieRequest $request, Categorie $category): RedirectResponse
    {
        $nom = trim($request->validated('name'));

        $category->update([
            'name' => $nom,
            'slug' => Str::slug($nom),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('succes', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(Categorie $category): RedirectResponse
    {
        if ($category->articles()->exists()) {
            return back()->withErrors([
                'categorie' => 'Impossible de supprimer cette catégorie car des articles y sont rattachés.',
            ]);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('succes', 'Catégorie supprimée avec succès.');
    }
}
