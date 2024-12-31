<?php

namespace Database\Seeders;

use App\Models\GoalPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoalPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //22
        //19 20 21 22
        for ($i = 1; $i <= 6; $i++) {
            GoalPlan::create([
                'goal_id' => 1,
                'plan_id' => $i == 6 ? 10 : $i,
            ]);
        }
        for ($i = 3; $i <= 9; $i++) {
            GoalPlan::create([
                'goal_id' => 2,
                'plan_id' => $i === 9 ? 11 : $i,
            ]);
        }
        for ($i = 2; $i <= 8; $i++) {
            GoalPlan::create([
                'goal_id' => 3,
                'plan_id' => $i == 8 ? 12 : $i,
            ]);
        }
        for ($i = 1; $i <= 7; $i++) {
            GoalPlan::create([
                'goal_id' => 4,
                'plan_id' => $i == 7 ? 13 : $i,
            ]);
        }
        GoalPlan::create([
            'goal_id' => 1,
            'plan_id' => 14,
        ]);
        GoalPlan::create([
            'goal_id' => 2,
            'plan_id' => 14,
        ]);
        GoalPlan::create([
            'goal_id' => 3,
            'plan_id' => 14,
        ]);
        GoalPlan::create([
            'goal_id' => 4,
            'plan_id' => 14,
        ]);
        GoalPlan::create([
            'goal_id' => 1,
            'plan_id' => 15,
        ]);
        GoalPlan::create([
            'goal_id' => 2,
            'plan_id' => 15,
        ]);
        GoalPlan::create([
            'goal_id' => 3,
            'plan_id' => 15,
        ]);
        GoalPlan::create([
            'goal_id' => 4,
            'plan_id' => 15,
        ]);
    }
}
