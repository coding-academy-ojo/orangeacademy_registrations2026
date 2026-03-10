<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'document_requirement_id' => \App\Models\DocumentRequirement::factory(),
            'file_path' => 'documents/fake_path_' . $this->faker->word . '.pdf',
            'is_verified' => $this->faker->boolean(70), // 70% chance of being verified
        ];
    }
}
