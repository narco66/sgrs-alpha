<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('user'));
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $actor = $this->user();

        // Seul l'utilisateur lui-même peut modifier son mot de passe via ce formulaire.
        $passwordRule = ($actor && $actor->id === $user->id)
            ? ['nullable', 'confirmed', Password::defaults()]
            : ['nullable', 'prohibited'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            // La table est nommée "utilisateurs" dans la base
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('utilisateurs', 'email')->ignore($user->id)],
            'password' => $passwordRule,
            'delegation_id' => ['nullable', 'exists:delegations,id'],
            'service' => ['nullable', 'string', 'max:255'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ];
    }
}

