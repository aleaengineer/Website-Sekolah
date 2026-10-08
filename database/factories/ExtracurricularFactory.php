<?php

namespace Database\Factories;

use App\Models\Extracurricular;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Extracurricular>
 */
class ExtracurricularFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Pramuka', 'Paskibra', 'Futsal', 'Bola Voli', 'Badminton', 'Seni Tari', 'Marawis', 'PMR', 'Karya Ilmiah Remaja', 'Pencak Silat']);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(2),
            'coach_name' => fake()->name(),
            'schedule' => fake()->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']).', 14.00 - 16.00 WIB',
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
