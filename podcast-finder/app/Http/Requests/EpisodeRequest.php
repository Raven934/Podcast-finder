<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EpisodeRequest extends FormRequest
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
            'description' => 'nullable|string|max:10000',
            'audio_path' => 'required|string|max:255',
            'podcast_id' => 'required|integer|exists:podcasts,id',
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
            'title.required' => 'The episode title is required.',
            'title.max' => 'The episode title must not exceed 255 characters.',
            'description.max' => 'The episode description must not exceed 10,000 characters.',
            'audio_path.required' => 'The audio file path is required.',
            'audio_path.max' => 'The audio path must not exceed 255 characters.',
            'podcast_id.required' => 'The podcast ID is required.',
            'podcast_id.exists' => 'The selected podcast does not exist.',
        ];
    }
}
