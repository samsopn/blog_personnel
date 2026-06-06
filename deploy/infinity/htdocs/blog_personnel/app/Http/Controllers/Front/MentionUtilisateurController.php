<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MentionUtilisateurController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $recherche = Utilisateur::normaliserUsername((string) $request->string('q', ''));

        $utilisateurs = Utilisateur::query()
            ->select(['username', 'name'])
            ->when(
                $recherche !== '',
                fn ($query) => $query->where('username', 'like', $recherche.'%')
            )
            ->orderBy('username')
            ->limit(8)
            ->get();

        return response()->json(
            $utilisateurs->map(fn (Utilisateur $utilisateur): array => [
                'username' => $utilisateur->username,
                'name' => $utilisateur->name,
            ])
        );
    }
}
