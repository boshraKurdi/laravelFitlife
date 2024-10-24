<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\GoalPlanLevel;
use App\Models\PlanLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GoalPlanLevel>
 */
class GoalPlanLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $goal = Goal::inRandomOrder()->first();

        $GoalPlanLevelIds = GoalPlanLevel::where('goal_id', $goal->id)->pluck('plan_level_id')->toArray();

        $PlanLevel = PlanLevel::whereNotIn('id', $GoalPlanLevelIds)
            ->inRandomOrder()
            ->first();

        if (!$PlanLevel) {
            $PlanLevel = PlanLevel::inRandomOrder()->first();
        }
        return [
            'goal_id' => $goal->id,
            'plan_level_id' => $PlanLevel->id,
        ];
    }
}
