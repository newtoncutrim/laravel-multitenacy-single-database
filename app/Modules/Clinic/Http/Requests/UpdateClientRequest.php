<?php

namespace App\Modules\Clinic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('client')) ?? false;
    }

    public function rules(): array
    {
        return [
            'branch_id' => [
                'sometimes',
                'nullable',
                Rule::exists('branches', 'id')
                    ->where('tenant_id', $this->user()->tenant_id),
            ],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'document' => ['sometimes', 'nullable', 'string', 'max:30'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:5000'],
        ];
    }
}
