<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policy DocumentPolicy utilisée au niveau du contrôleur
    }

    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string', 'max:5000'],
            'file'             => ['required', 'file', 'max:512000'], // 500 Mo
            'document_type'    => ['nullable', 'in:ordre_du_jour,rapport,pv,presentation,note,autre'],
            'document_type_id' => ['nullable', 'exists:types_documents,id'],
            'meeting_id'       => ['nullable', 'exists:reunions,id'],
            'is_shared'        => ['nullable', 'boolean'],
        ];
    }
}
