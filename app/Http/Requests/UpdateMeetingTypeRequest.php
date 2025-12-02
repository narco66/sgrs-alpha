<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('meeting_type')?->id ?? $this->route('meeting_type');

        return [
            'name'                       => ['required', 'string', 'max:255'],
            'code'                       => ['required', 'string', 'max:20', 'unique:types_reunions,code,' . $id],
            'color'                      => ['nullable', 'string', 'max:20'],
            'sort_order'                 => ['nullable', 'integer', 'min:0'],
            'requires_president_approval'=> ['nullable', 'boolean'],
            'requires_sg_approval'       => ['nullable', 'boolean'],
            'description'                => ['nullable', 'string'],
            'is_active'                  => ['nullable', 'boolean'],
            'meeting_type_id'            => ['nullable', 'exists:types_reunions,id'],
            'committee_id'               => ['nullable', 'exists:comites,id'],
        ];
    }
}
