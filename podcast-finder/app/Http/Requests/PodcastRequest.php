<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PodcastRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image_path' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:100',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The podcast title is required.',
            'title.max' => 'The podcast title must not exceed 255 characters.',
            'description.max' => 'The podcast description must not exceed 5000 characters.',
            'image_path.max' => 'The image path must not exceed 255 characters.',
            'genre.max' => 'The genre must not exceed 100 characters.',
            'user_id.required' => 'The user ID is required.',
            'user_id.exists' => 'The selected user does not exist.',
        ];
    }
}
