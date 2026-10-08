<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'image' => null,
            'description' => fake()->sentence(10),
            'taken_at' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'sort_order' => 0,
        ];
    }
}
