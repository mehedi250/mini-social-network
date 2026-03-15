<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePostRequest extends FormRequest
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
            'content' => ['nullable', 'string', 'max:2000', 'required_without:media'],
            'media' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,mp4,mov,avi', 'max:20480'],
            'privacy' => ['required', 'in:PUBLIC,FOLLOWERS,PRIVATE'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required_without' => 'Either content or media must be provided.',
            'media.max' => 'The media file must not be larger than 20MB.',
            'media.mimes' => 'The media file must be an image or video.',
            'privacy.in' => 'The privacy must be one of: PUBLIC, FOLLOWERS, PRIVATE.',
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
