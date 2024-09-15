<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

class DescriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }

    /**
     * Creates a description for experiences.
     *
     * @return Factory
     */
    public function forExp(): Factory
    {
        return $this->state(
            function (array $attributes) {
                return [
                    'lang'                 => [
                        'EN',
                        'PT',
                    ][random_int(0, 1)],
                    'description'          => fake()->text(500),
                    'name'                 => fake()->name(),
                    'type'                 => 'exp',
                    'descriptionable_id'   => Experience::factory(),
                    'descriptionable_type' => function (array $attributes) {
                        return Experience::find($attributes['descriptionable_id'])->getMorphClass();
                    },
                ];
            }
        );
    }
}
