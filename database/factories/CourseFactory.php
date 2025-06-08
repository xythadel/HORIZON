<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
    {
    return [
        'name' => fake()->words(2, true),
        'description' => fake()->paragraph(30), // or sentence(25) or paragraphs(3)
    ];
    }

}
