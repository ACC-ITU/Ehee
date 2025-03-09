<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VehicleAdvanceSearchRequest extends FormRequest
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
            'owner' => ['nullable', 'string'],
            'registration_date' => ['nullable', 'date'],
            'registration_date_operator' => ['nullable', 'string', 'in:=,>,<'],
            'registration_number' => ['nullable', 'string'],
            'island' => ['nullable', 'string'],
            'atoll' => ['nullable', 'string'],
            'domain' => ['nullable', 'integer'],
            'status' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer'],
        ];
    }
}
