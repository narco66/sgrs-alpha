<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('documentType'));
    }

    public function rules(): array
    {
        $documentType = $this->route('documentType');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('document_types')->ignore($documentType->id)],
            'code' => ['required', 'string', 'max:50', Rule::unique('document_types')->ignore($documentType->id)],
            'description' => ['nullable', 'string'],
            'requires_validation' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}

