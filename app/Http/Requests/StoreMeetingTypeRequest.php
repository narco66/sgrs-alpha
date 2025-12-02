<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeetingTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust authorization logic as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:types_reunions,code'],
            'color' => ['nullable', 'string', 'max:7'],
            'sort_order' => ['nullable', 'integer'],
            'requires_president_approval' => ['boolean'],
            'requires_sg_approval' => ['boolean'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],

        ];
    }
}
