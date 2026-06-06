<?php

namespace App\Http\Requests\Auth;

use App\Models\Utilisateur;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class InscriptionRequest extends FormRequest
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
                Rule::unique(Utilisateur::class, 'username'),
            ],
            'email' => ['required', 'string', 'email', 'max:150', Rule::unique(Utilisateur::class, 'email')],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom complet est obligatoire.',
            'username.required' => 'Le pseudo est obligatoire.',
            'username.min' => 'Le pseudo doit contenir au moins 3 caractères.',
            'username.max' => 'Le pseudo ne peut pas dépasser 30 caractères.',
            'username.regex' => 'Le pseudo doit commencer par une lettre et ne contenir que lettres, chiffres ou _.',
            'username.unique' => 'Ce pseudo est déjà utilisé.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ];
    }
}
