<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\PlanLevelMeal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanLevelMealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 28 / 29/ 30/ 31
        for ($i = 1; $i <= 4; $i++) {
            for ($j = 1; $j <= 7; $j++) {
                for ($d = 0; $d < 4; $d++) {
                    PlanLevelMeal::create([
                        'plan_level_id' => 28,
                        'meal_id' => Meal::inRandomOrder()->first()->id,
                        'day' => $j,
                        'week' => $i,
                        'breakfast' => rand(0, 1),
                        'lunch' => rand(0, 1),
                        'dinner' => rand(0, 1),
                        'snacks' => rand(0, 1),
                    ]);
                }
            }
        }
        for ($i = 1; $i <= 4; $i++) {
            for ($j = 1; $j <= 7; $j++) {
                for ($d = 0; $d <= 4; $d++) {
                    PlanLevelMeal::create([
                        'plan_level_id' => 29,
                        'meal_id' => Meal::inRandomOrder()->first()->id,
                        'day' => $j,
                        'week' => $i,
                        'breakfast' => rand(0, 1),
                        'lunch' => rand(0, 1),
                        'dinner' => rand(0, 1),
                        'snacks' => rand(0, 1),
                    ]);
                }
            }
        }
        for ($i = 1; $i <= 4; $i++) {
            for ($j = 1; $j <= 7; $j++) {
                for ($d = 0; $d <= 4; $d++) {
                    PlanLevelMeal::create([
                        'plan_level_id' => 30,
                        'meal_id' => Meal::inRandomOrder()->first()->id,
                        'day' => $j,
                        'week' => $i,
                        'breakfast' => rand(0, 1),
                        'lunch' => rand(0, 1),
                        'dinner' => rand(0, 1),
                        'snacks' => rand(0, 1),
                    ]);
                }
            }
        }
        for ($i = 1; $i <= 4; $i++) {
            for ($j = 1; $j <= 7; $j++) {
                for ($d = 0; $d <= 4; $d++) {
                    PlanLevelMeal::create([
                        'plan_level_id' => 31,
                        'meal_id' => Meal::inRandomOrder()->first()->id,
                        'day' => $j,
                        'week' => $i,
                        'breakfast' => rand(0, 1),
                        'lunch' => rand(0, 1),
                        'dinner' => rand(0, 1),
                        'snacks' => rand(0, 1),
                    ]);
                }
            }
        }
    }
}
