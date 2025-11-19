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
            'description' => 'required|string|max:10000|min:10',
            'audio_file' => 'required_without:audio_path|file|mimes:mp3,wav,m4a,aac,ogg|max:51200', // 50MB max
            'audio_path' => 'required_without:audio_file|string|max:500|url',
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
            'description.required' => 'The episode description is required.',
            'description.min' => 'The episode description must be at least 10 characters.',
            'description.max' => 'The episode description must not exceed 10,000 characters.',
            'audio_file.required_without' => 'Either an audio file or audio path URL is required.',
            'audio_file.file' => 'The audio file must be a valid file.',
            'audio_file.mimes' => 'The audio file must be of type: mp3, wav, m4a, aac, or ogg.',
            'audio_file.max' => 'The audio file size must not exceed 50MB.',
            'audio_path.required_without' => 'Either an audio path URL or audio file is required.',
            'audio_path.url' => 'The audio path must be a valid URL.',
            'audio_path.max' => 'The audio path must not exceed 500 characters.'
        
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default values
        if (!$this->has('status')) {
            $this->merge(['status' => 'draft']);
        }
        
        if (!$this->has('is_published')) {
            $this->merge(['is_published' => false]);
        }
        
        // Convert string booleans to actual booleans
        if ($this->has('is_published')) {
            $this->merge([
                'is_published' => filter_var($this->is_published, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Ensure published_at is set when status is scheduled
            if ($this->status === 'scheduled' && !$this->filled('published_at')) {
                $validator->errors()->add('published_at', 'Publication date is required when status is scheduled.');
            }

            // Ensure published_at is in the future for scheduled episodes
            if ($this->status === 'scheduled' && $this->filled('published_at')) {
                if (strtotime($this->published_at) <= now()->timestamp) {
                    $validator->errors()->add('published_at', 'Scheduled publication date must be in the future.');
                }
            }
        });
    }
}
