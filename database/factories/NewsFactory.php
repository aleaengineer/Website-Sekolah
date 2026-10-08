<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(5),
            'excerpt' => fake()->sentence(16),
            'body' => collect(fake()->paragraphs(5))->map(fn (string $p) => "<p>{$p}</p>")->implode("\n"),
            'cover_image' => null,
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'is_published' => true,
        ];
    }
}
