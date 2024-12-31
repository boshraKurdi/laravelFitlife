<?php

namespace Database\Factories;

use App\Models\Exercise;
use App\Models\Plan;
use App\Models\PlanLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlanLevelExercise>
 */
class PlanExerciseFactory extends Factory
{
    private static $dayCounter = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plan_level_id = PlanLevel::inRandomOrder()->first();
        if (self::$dayCounter > 7) {
            self::$dayCounter = 1;
        }
        return [
            'plan_id' => $plan_level_id->id,
            'exercise_id' => Exercise::inRandomOrder()->first()->id,
            'day' => self::$dayCounter++,
            'week' => '1'
        ];
    }
}
