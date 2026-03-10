<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence,
            'type' => $this->faker->randomElement(['code', 'english', 'iq']),
            'max_score' => 100,
            'is_published' => true,
        ];
    }
}
