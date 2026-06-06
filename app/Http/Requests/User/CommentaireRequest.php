<?php

namespace App\Http\Requests\User;

use App\Models\Commentaire;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists(Commentaire::class, 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Le commentaire ne peut pas être vide.',
            'parent_id.exists' => 'Le commentaire parent est invalide.',
        ];
    }

    protected function getRedirectUrl(): string
    {
        $article = $this->route('article');
        $parentId = $this->input('parent_id');
        $fragment = $parentId ? 'commentaire-'.$parentId : 'commentaires';

        return route('articles.show', $article->slug).'#'.$fragment;
    }
}
