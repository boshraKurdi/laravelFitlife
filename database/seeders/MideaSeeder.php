<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Goal;
use App\Models\Gym;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MideaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $image_1 = storage_path('images\blog-two.jpg');
        // $image_2 = storage_path('images\class-one.jpg');
        $image_3 = storage_path('images\class-two.jpg');
        $image_4 = storage_path('images\image.png');
        $image_5 = storage_path('images\about-coach.jpg');
        for ($i = 1; $i <= 20; $i++) {
            $plan = Plan::find($i);
            $plan
                ->addMedia($image_1)
                ->preservingOriginal()
                ->toMediaCollection('plans');
        }
        $image_goal_1 = storage_path('images\goal_1.jpg');
        $image_goal_2 = storage_path('images\goal_2.jpg');
        $image_goal_3 = storage_path('images\goal_3.jpg');
        $image_goal_4 = storage_path('images\goal_4.jpg');
        $image_goal_5 = storage_path('images\goal_5.jpg');
        $image = [$image_goal_1, $image_goal_2, $image_goal_3, $image_goal_4, $image_goal_5];
        for ($i = 1; $i <= 5; $i++) {
            $goal = Goal::find($i);
            $goal
                ->addMedia($image[$i - 1])
                ->preservingOriginal()
                ->toMediaCollection('goals');
        }
        for ($i = 1; $i <= 8; $i++) {
            $gym = Gym::find($i);
            $gym
                ->addMedia($image_3)
                ->preservingOriginal()
                ->toMediaCollection('gyms');
        }
        for ($i = 1; $i <= 20; $i++) {
            $exercise = Exercise::find($i);
            $exercise
                ->addMedia($image_4)
                ->preservingOriginal()
                ->toMediaCollection('exercises');
        }
        for ($i = 2; $i < 11; $i++) {
            $coach = User::find($i);
            $coach
                ->addMedia($image_5)
                ->preservingOriginal()
                ->toMediaCollection('users');
        }
    }
}
