<?php

namespace Database\Factories;

use App\Models\Meal;
use App\Models\PlanLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlanLevelMeal>
 */
class PlanLevelMealFactory extends Factory
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
            'plan_level_id' => $plan_level_id->id,
            'meal_id' => Meal::inRandomOrder()->first()->id,
            'day' => self::$dayCounter++,
            'week' => '1',
            'breakfast' => rand(0, 1),
            'lunch' => rand(0, 1),
            'dinner' => rand(0, 1),
            'snacks' => rand(0, 1),
        ];
    }
}
