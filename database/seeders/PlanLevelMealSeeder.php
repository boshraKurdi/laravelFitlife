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
        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];
        $b = [1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 1, 1];
        $l = [0, 1, 1, 1, 0, 0, 1, 0, 1, 0, 0, 0, 0];
        $dd = [0, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0];
        // 28 / 29/ 30/ 31
        for ($i = 1; $i <= 4; $i++) {
            for ($j = 1; $j <= 7; $j++) {
                $excludedNumbers = [];
                for ($d = 0; $d < 6; $d++) {
                    do {
                        $randomNumber = $array[array_rand($array)];
                    } while (in_array($randomNumber, $excludedNumbers));
                    array_push($excludedNumbers, $randomNumber);
                    PlanLevelMeal::create([
                        'plan_level_id' => 28,
                        'meal_id' => $randomNumber,
                        'day' => $j,
                        'week' => $i,
                        'breakfast' => $b[$d],
                        'lunch' => $l[$d],
                        'dinner' => $dd[$d],
                        'snacks' => rand(0, 1),
                    ]);
                }
            }
        }
        for ($i = 1; $i <= 4; $i++) {
            for ($j = 1; $j <= 7; $j++) {
                $excludedNumbers = [];
                for ($d = 6; $d < 13; $d++) {
                    do {
                        $randomNumber = $array[array_rand($array)];
                    } while (in_array($randomNumber, $excludedNumbers));
                    array_push($excludedNumbers, $randomNumber);
                    PlanLevelMeal::create([
                        'plan_level_id' => 29,
                        'meal_id' => $randomNumber,
                        'day' => $j,
                        'week' => $i,
                        'breakfast' => $b[$d],
                        'lunch' => $l[$d],
                        'dinner' => $dd[$d],
                        'snacks' => rand(0, 1),
                    ]);
                }
            }
        }
        for ($i = 1; $i <= 4; $i++) {
            for ($j = 1; $j <= 7; $j++) {
                $excludedNumbers = [];
                for ($d = 4; $d < 10; $d++) {
                    do {
                        $randomNumber = $array[array_rand($array)];
                    } while (in_array($randomNumber, $excludedNumbers));
                    array_push($excludedNumbers, $randomNumber);
                    PlanLevelMeal::create([
                        'plan_level_id' => 30,
                        'meal_id' => $randomNumber,
                        'day' => $j,
                        'week' => $i,
                        'breakfast' => $b[$d],
                        'lunch' => $l[$d],
                        'dinner' => $dd[$d],
                        'snacks' => rand(0, 1),
                    ]);
                }
            }
        }
        for ($i = 1; $i <= 4; $i++) {
            for ($j = 1; $j <= 7; $j++) {
                $excludedNumbers = [];
                for ($d = 3; $d < 9; $d++) {
                    do {
                        $randomNumber = $array[array_rand($array)];
                    } while (in_array($randomNumber, $excludedNumbers));
                    array_push($excludedNumbers, $randomNumber);
                    PlanLevelMeal::create([
                        'plan_level_id' => 31,
                        'meal_id' => $randomNumber,
                        'day' => $j,
                        'week' => $i,
                        'breakfast' => $b[$d],
                        'lunch' => $l[$d],
                        'dinner' => $dd[$d],
                        'snacks' => rand(0, 1),
                    ]);
                }
            }
        }
    }
}
