<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'code'        => [
                'required', 
                'string', 
                'max:50', 
                Rule::unique('salles', 'code')->ignore($this->route('room'))
            ],
            'capacity'    => ['required', 'integer', 'min:1', 'max:1000'],
            'location'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            'equipments'  => ['nullable', 'array'],
            'equipments.*' => ['string', 'max:100'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la salle est obligatoire.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'code.required' => 'Le code de la salle est obligatoire.',
            'code.unique' => 'Ce code est déjà utilisé par une autre salle.',
            'capacity.required' => 'La capacité est obligatoire.',
            'capacity.min' => 'La capacité doit être d\'au moins 1 personne.',
            'capacity.max' => 'La capacité ne peut pas dépasser 1000 personnes.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou WebP.',
            'image.max' => 'L\'image ne peut pas dépasser 5 Mo.',
        ];
    }
}
