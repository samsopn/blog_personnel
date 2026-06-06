<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'tags' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'content.required' => 'Le contenu est obligatoire.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut doit être "Brouillon" ou "Publié".',
            'published_at.date' => 'La date de publication n\'est pas valide.',
            'image.image' => 'Le fichier image est invalide.',
            'image.mimes' => 'Le format image doit être jpg, jpeg, png ou webp.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
            'categories.required' => 'Sélectionnez au moins une catégorie.',
            'categories.*.exists' => 'Une catégorie sélectionnée est invalide.',
            'tags.max' => 'La liste des tags est trop longue.',
        ];
    }
}
