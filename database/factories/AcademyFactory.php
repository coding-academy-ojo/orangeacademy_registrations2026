<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AcademyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Academy',
            'location' => $this->faker->city,
            'description' => $this->faker->sentence,
        ];
    }
}
