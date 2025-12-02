<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La Policy RoomPolicy s’appliquera aussi côté contrôleur
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'code'        => ['required', 'string', 'max:50', 'unique:rooms,code'],
            'capacity'    => ['required', 'integer', 'min:0'],
            'location'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }
}
