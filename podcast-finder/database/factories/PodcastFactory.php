<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Podcast>
 */
class PodcastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'description' => fake()->paragraph(),
            'image_path' => fake()->imageUrl(),
            'genre' => fake()->randomElement(['development','funny','documentary','health']), 
            'user_id'=>User::where('role','host')->get(['id'])->random(),
        ];
    }
}
