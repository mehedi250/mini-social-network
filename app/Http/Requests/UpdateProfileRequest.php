<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProfileRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'profile_image' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:255',
            'profession' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'current_city' => 'nullable|string|max:255',
            'home_city' => 'nullable|string|max:255',
            'relationship_status' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'website' => 'nullable|url|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'profile_image.image' => 'The profile image must be an image file.',
            'profile_image.max' => 'The profile image must not be larger than 2MB.',
            'cover_image.image' => 'The cover image must be an image file.',
            'cover_image.max' => 'The cover image must not be larger than 2MB.',
            'bio.max' => 'The bio must not be longer than 255 characters.',
            'profession.max' => 'The profession must not be longer than 255 characters.',
            'company.max' => 'The company must not be longer than 255 characters.',
            'education.max' => 'The education must not be longer than 255 characters.',
            'current_city.max' => 'The current city must not be longer than 255 characters.',
            'home_city.max' => 'The home city must not be longer than 255 characters.',
            'relationship_status.max' => 'The relationship status must not be longer than 255 characters.',
            'gender.max' => 'The gender must not be longer than 255 characters.',
            'date_of_birth.date' => 'The date of birth must be a valid date.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'message' => 'Validation failed. Please check your inputs.',
            'data'    => null,
            'errors'  => $validator->errors(),
            'meta'    => null
        ], 422);

        throw new HttpResponseException($response);
    }
}
