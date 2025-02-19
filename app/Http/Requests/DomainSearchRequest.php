<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DomainSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'owner' => ['required', 'string', 'starts_with:A', 'size:7', 'alpha_num'],
        ];
    }

    public function messages(): array
    {
        return [
            'owner.starts_with' => 'NID must start with an A',
            'owner.size' => 'NID must be 7 characters',
            'owner.alpha_num' => 'Invalid NID number',
        ];
    }
}
