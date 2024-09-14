<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => fake()->name(),
            'email'       => fake()->unique()->safeEmail(),
            'profile_img' => 'placeholder',
            'password'    => Hash::make('P4$$w0rd'),
        ];
    }
}
