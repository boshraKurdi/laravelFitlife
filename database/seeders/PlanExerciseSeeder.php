<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\PlanExercise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        for ($i = 1; $i <= 9; $i++) {
            for ($l = 1; $l <= 2; $l++) {
                for ($j = 1; $j <= 7; $j++) {
                    $excludedNumbers = [];
                    for ($d = 1; $d <= 3; $d++) {
                        do {
                            $randomNumber = $array[array_rand($array)];
                        } while (in_array($randomNumber, $excludedNumbers));
                        array_push($excludedNumbers, $randomNumber);
                        PlanExercise::create([
                            'plan_id' => $i,
                            'exercise_id' => $randomNumber,
                            'day' => $j,
                            'week' => $l
                        ]);
                    }
                }
            }
        }
    }
}
