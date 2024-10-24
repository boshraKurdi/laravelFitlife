<?php

namespace Database\Factories;

use App\Models\Level;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlanLevel>
 */
class PlanLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plan_id' => Plan::inRandomOrder()->first()->id,
            'level_id' => Level::inRandomOrder()->first()->id,
        ];
    }
}
