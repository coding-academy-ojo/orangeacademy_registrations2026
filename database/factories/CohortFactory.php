<?php

namespace Database\Factories;

use App\Models\Academy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CohortFactory extends Factory
{
    public function definition(): array
    {
        return [
            'academy_id' => Academy::factory(),
            'name' => 'Cohort ' . $this->faker->numberBetween(1, 100) . ' - ' . $this->faker->words(2, true),
            'start_date' => Carbon::now()->addDays(rand(-30, 30)),
            'end_date' => Carbon::now()->addMonths(6),
            'status' => $this->faker->randomElement(['active', 'completed', 'cancelled']),
        ];
    }
}
