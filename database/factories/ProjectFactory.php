<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id'    => User::factory(),
            'title'       => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'due_date'    => fake()->dateTimeBetween('now', '+6 months'),
            'status'      => fake()->randomElement(['pending', 'in_progress', 'completed']),
        ];
    }
}
