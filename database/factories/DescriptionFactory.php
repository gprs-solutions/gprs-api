<?php

namespace Database\Factories;

use App\Models\Certification;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Project;
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

    /**
     * Creates a description for certifications.
     *
     * @return Factory
     */
    public function forCert(): Factory
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
                    'type'                 => 'cert',
                    'descriptionable_id'   => Certification::factory(),
                    'descriptionable_type' => function (array $attributes) {
                        return Certification::find($attributes['descriptionable_id'])->getMorphClass();
                    },
                ];
            }
        );
    }

    /**
     * Creates a description for educations.
     *
     * @return Factory
     */
    public function forEdu(): Factory
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
                    'type'                 => 'edu',
                    'descriptionable_id'   => Education::factory(),
                    'descriptionable_type' => function (array $attributes) {
                        return Education::find($attributes['descriptionable_id'])->getMorphClass();
                    },
                ];
            }
        );
    }

    /**
     * Creates a description for projects.
     *
     * @return Factory
     */
    public function forProj(): Factory
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
                    'type'                 => 'proj',
                    'descriptionable_id'   => Project::factory(),
                    'descriptionable_type' => function (array $attributes) {
                        return Project::find($attributes['descriptionable_id'])->getMorphClass();
                    },
                ];
            }
        );
    }
}
