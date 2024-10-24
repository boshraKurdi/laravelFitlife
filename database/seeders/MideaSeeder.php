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
        $image_2 = storage_path('images\class-one.jpg');
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
        for ($i = 1; $i <= 10; $i++) {
            $goal = Goal::find($i);
            $goal
                ->addMedia($image_2)
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
