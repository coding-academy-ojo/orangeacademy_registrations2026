<?php

namespace Database\Factories;

use App\Models\{User, Assessment};
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentSubmissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'assessment_id' => Assessment::factory(),
            'status' => 'submitted',
            'submitted_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
