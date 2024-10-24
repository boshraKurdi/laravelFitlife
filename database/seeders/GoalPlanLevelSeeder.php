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
        GoalPlanLevel::factory(30)->create();
    }
}
