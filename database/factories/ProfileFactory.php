<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name_en' => $this->faker->firstName,
            'last_name_en' => $this->faker->lastName,
            'first_name_ar' => $this->faker->firstName, // Faker doesn't have AR by default in simple setup, just random
            'last_name_ar' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->dateTimeBetween('-30 years', '-18 years'),
            'nationality' => 'Jordanian',
            'city' => $this->faker->city,
            'address' => $this->faker->streetAddress,
            'education_level' => $this->faker->randomElement(['High School', 'Bachelor', 'Master']),
            'field_of_study' => $this->faker->word,
        ];
    }
}
