<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEpisodeRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a,aac,ogg|max:51200', // 50MB max
            'audio_path' => 'nullable|string|max:500|url',
            'duration' => 'nullable|integer|min:1|max:86400', // Max 24 hours in seconds
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
            'title.required' => 'The episode title is required when provided.',
            'title.max' => 'The episode title must not exceed 255 characters.',
            'description.max' => 'The episode description must not exceed 10,000 characters.',
            'audio_file.file' => 'The audio file must be a valid file.',
            'audio_file.mimes' => 'The audio file must be of type: mp3, wav, m4a, aac, or ogg.',
            'audio_file.max' => 'The audio file size must not exceed 50MB.',
            'audio_path.url' => 'The audio path must be a valid URL.',
            'audio_path.max' => 'The audio path must not exceed 500 characters.'
        ];
    }
}
