<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
     * Get the "after" validation callables for the request.
     */
//    public function after(): array
//    {
//        $queries = collect(request())->except('page', 'status', 'registration_date_operator');
//        $allQueriesEmpty = $queries->every(function ($value, $key) {
//            return $value === null || $value === '' || $value === 'all';
//        });
//        return [
//            function (Validator $validator) use ($allQueriesEmpty) {
//                if ($allQueriesEmpty) {
//                    $validator->errors()->add(
//                        'owner',
//                        'You must provide at least one search parameter.'
//                    );
//                }
//            }
//        ];
//    }

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
