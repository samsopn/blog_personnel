<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentaireController extends Controller
{
    public function index(): View
    {
        return view('admin.commentaires.index', [
            'commentaires' => Commentaire::query()
                ->with(['auteur', 'article', 'parent'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function destroy(Commentaire $commentaire): RedirectResponse
    {
        $commentaire->delete();

        return redirect()
            ->route('admin.commentaires.index')
            ->with('succes', 'Commentaire supprimé avec succès.');
    }
}
