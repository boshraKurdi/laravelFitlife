<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            GoalSeeder::class,
            PlanSeeder::class,
            GoalPlanSeeder::class,
            GymSeeder::class,
            ExerciseSeeder::class,
            PlanExerciseSeeder::class,
            ServiceSeeder::class,
            ChatSeeder::class,
            GroupSeeder::class,
            MessageSeeder::class,
            SectionSeeder::class,
            GymSectionSeeder::class,
            CategorySeeder::class,
            MealSeeder::class,
            PlanMealSeeder::class,
            StepSeeder::class,
            RolesAndPermissionsSeeder::class,
            IngredientSeeder::class,
            MideaSeeder::class
        ]);
    }
}
