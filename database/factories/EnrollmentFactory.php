<?php

namespace Database\Factories;

use App\Models\{User, Cohort};
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    public function definition(): array
    {
        $status = $this->faker->randomElement(['applied', 'applied', 'accepted', 'rejected', 'enrolled']);
        return [
            'user_id' => User::factory(),
            'cohort_id' => Cohort::factory(),
            'status' => $status,
            'enrolled_at' => in_array($status, ['accepted', 'enrolled']) ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
