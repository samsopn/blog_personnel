<?php

namespace App\Http\Requests\User;

use App\Models\Utilisateur;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('username')) {
            $this->merge([
                'username' => Utilisateur::normaliserUsername((string) $this->input('username')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z][a-zA-Z0-9_]*$/',
                Rule::unique(Utilisateur::class, 'username')->ignore($this->user()->id),
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'username.required' => 'Le pseudo est obligatoire.',
            'username.unique' => 'Ce pseudo est déjà utilisé.',
            'username.regex' => 'Le pseudo doit commencer par une lettre et ne contenir que lettres, chiffres ou _.',
            'avatar.image' => 'Le fichier avatar est invalide.',
            'avatar.mimes' => 'L\'avatar doit être jpg, jpeg, png ou webp.',
            'avatar.max' => 'L\'avatar ne doit pas dépasser 2 Mo.',
        ];
    }
}
