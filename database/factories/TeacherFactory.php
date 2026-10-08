<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'position' => fake()->randomElement(['Kepala Sekolah', 'Wakil Kepala Sekolah', 'Guru', 'Guru', 'Guru', 'Tata Usaha', 'Operator Dapodik']),
            'subject' => fake()->randomElement(['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA', 'IPS', 'PPKn', 'Pendidikan Agama', 'PJOK', 'Seni Budaya', 'Prakarya', 'Informatika']),
            'photo' => null,
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
