<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => fake()->text(),
            'start' => fake()->date('Y-m-d'),
            'end' => fake()->date('Y-m-d'),
        ];
    }
}
