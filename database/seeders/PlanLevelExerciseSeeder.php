<?php

namespace Database\Seeders;

use App\Models\PlanLevelExercise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanLevelExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlanLevelExercise::factory(40)->create();
    }
}
