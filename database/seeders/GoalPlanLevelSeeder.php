<?php

namespace Database\Seeders;

use App\Models\GoalPlanLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoalPlanLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //22
        //19 20 21 22
        for ($i = 1; $i <= 6; $i++) {
            GoalPlanLevel::create([
                'goal_id' => 1,
                'plan_level_id' => $i == 6 ? 28 : $i,
            ]);
        }
        for ($i = 6; $i <= 12; $i++) {
            GoalPlanLevel::create([
                'goal_id' => 2,
                'plan_level_id' => $i == 12 ? 29 : $i,
            ]);
        }
        for ($i = 12; $i <= 19; $i++) {
            GoalPlanLevel::create([
                'goal_id' => 3,
                'plan_level_id' => $i == 19 ? 30 : $i,
            ]);
        }
        for ($i = 1; $i <= 7; $i++) {
            GoalPlanLevel::create([
                'goal_id' => 4,
                'plan_level_id' => $i == 7 ? 31 : $i,
            ]);
        }
    }
}
