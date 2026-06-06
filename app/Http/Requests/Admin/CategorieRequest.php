<?php

namespace App\Http\Requests\Admin;

use App\Models\Categorie;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategorieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categorie = $this->route('category');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique(Categorie::class, 'name')->ignore($categorie?->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la catégorie est obligatoire.',
            'name.max' => 'Le nom de la catégorie ne doit pas dépasser 100 caractères.',
            'name.unique' => 'Cette catégorie existe déjà.',
        ];
    }
}
