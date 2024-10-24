<?php

namespace Database\Factories;

use App\Models\GoalPlanLevel;
use App\Models\Target;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Target>
 */
class TargetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();

        $usedGoalPlanLevelIds = Target::where('user_id', $user->id)->pluck('goal_plan_level_id')->toArray();

        $goalPlanLevel = GoalPlanLevel::whereNotIn('id', $usedGoalPlanLevelIds)
            ->inRandomOrder()
            ->first();

        if (!$goalPlanLevel) {
            $goalPlanLevel = GoalPlanLevel::inRandomOrder()->first();
        }

        return [
            'user_id' => $user->id,
            'goal_plan_level_id' => $goalPlanLevel->id,
            'calories' => rand(100, 5000),
            'duration' =>  rand(1, 30) . ' days',
            'rate' =>  rand(0, 100) . '%'
        ];
    }
}
