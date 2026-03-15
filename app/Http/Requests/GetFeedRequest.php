<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetFeedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:5', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'per_page.min' => 'The per page must be at least 5.',
            'per_page.max' => 'The per page must be at most 50.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status'  => 'error',
            'message' => 'Validation failed. Please check your inputs.',
            'errors'  => $validator->errors()
        ], 422);

        throw new HttpResponseException($response);
    }
}
