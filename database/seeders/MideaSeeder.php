<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Goal;
use App\Models\Gym;
use App\Models\Plan;
use App\Models\Section;
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
        $image_4 = storage_path('images\image.png');
        $image_5 = storage_path('images\about-coach.jpg');
        $image_plan_1 = storage_path('images\thigh.png');
        $image_plan_2 = storage_path('images\lower.png');
        $image_plan_3 = storage_path('images\upper.png');
        $image_plan_4 = storage_path('images\chest.png');
        $image_plan_5 = storage_path('images\back.png');
        $image_plan_6 = storage_path('images\arm.png');
        $image_plan_7 = storage_path('images\shoulder.png');
        $image_plan_8 = storage_path('images\waistline.png');
        $image_plan_9 = storage_path('images\thigh.png');

        $plan_1 = Plan::find(1);
        $plan_1
            ->addMedia($image_plan_1)
            ->preservingOriginal()
            ->toMediaCollection('plans');
        $plan_2 = Plan::find(2);
        $plan_2
            ->addMedia($image_plan_2)
            ->preservingOriginal()
            ->toMediaCollection('plans');
        $plan_3 = Plan::find(3);
        $plan_3
            ->addMedia($image_plan_3)
            ->preservingOriginal()
            ->toMediaCollection('plans');
        $plan_4 = Plan::find(4);
        $plan_4
            ->addMedia($image_plan_4)
            ->preservingOriginal()
            ->toMediaCollection('plans');
        $plan_5 = Plan::find(5);
        $plan_5
            ->addMedia($image_plan_5)
            ->preservingOriginal()
            ->toMediaCollection('plans');
        $plan_6 = Plan::find(6);
        $plan_6
            ->addMedia($image_plan_6)
            ->preservingOriginal()
            ->toMediaCollection('plans');
        $plan_7 = Plan::find(7);
        $plan_7
            ->addMedia($image_plan_7)
            ->preservingOriginal()
            ->toMediaCollection('plans');
        $plan_8 = Plan::find(8);
        $plan_8
            ->addMedia($image_plan_8)
            ->preservingOriginal()
            ->toMediaCollection('plans');
        $plan_9 = Plan::find(9);
        $plan_9
            ->addMedia($image_plan_9)
            ->preservingOriginal()
            ->toMediaCollection('plans');
        $image_goal_1 = storage_path('images\goal_1.png');
        $image_goal_2 = storage_path('images\goal_2.png');
        $image_goal_4 = storage_path('images\goal_3.png');
        $image_goal_3 = storage_path('images\goal_4.png');
        $goal_1 = Goal::find(1);
        $goal_1
            ->addMedia($image_goal_1)
            ->preservingOriginal()
            ->toMediaCollection('goals');
        $goal_2 = Goal::find(2);
        $goal_2
            ->addMedia($image_goal_2)
            ->preservingOriginal()
            ->toMediaCollection('goals');
        $goal_3 = Goal::find(3);
        $goal_3
            ->addMedia($image_goal_3)
            ->preservingOriginal()
            ->toMediaCollection('goals');
        $goal_4 = Goal::find(4);
        $goal_4
            ->addMedia($image_goal_4)
            ->preservingOriginal()
            ->toMediaCollection('goals');
        $image_section_1 = storage_path('images\section_1.jfif');
        $image_section_2 = storage_path('images\section_2.jfif');
        $image_section_3 = storage_path('images\section_3.jfif');
        $image_section_4 = storage_path('images\section_4.jfif');
        $image_section_5 = storage_path('images\section_5.jpg');
        $image_section_6 = storage_path('images\about-coach.jpg');
        $section_1 = Section::find(1);
        $section_1
            ->addMedia($image_section_1)
            ->preservingOriginal()
            ->toMediaCollection('sections');
        $section_2 = Section::find(2);
        $section_2
            ->addMedia($image_section_2)
            ->preservingOriginal()
            ->toMediaCollection('sections');
        $section_3 = Section::find(3);
        $section_3
            ->addMedia($image_section_3)
            ->preservingOriginal()
            ->toMediaCollection('sections');
        $section_4 = Section::find(4);
        $section_4
            ->addMedia($image_section_4)
            ->preservingOriginal()
            ->toMediaCollection('sections');
        $section_5 = Section::find(5);
        $section_5
            ->addMedia($image_section_5)
            ->preservingOriginal()
            ->toMediaCollection('sections');
        $section_6 = Section::find(6);
        $section_6
            ->addMedia($image_section_6)
            ->preservingOriginal()
            ->toMediaCollection('sections');

        $image_gym_1 = storage_path('images\gym_1.jpg');
        $image_gym_2 = storage_path('images\gym_2.jpg');
        $image_gym_3 = storage_path('images\gym_3.png');
        $image_gym_4 = storage_path('images\gym_4.jpg');
        $image_gym_5 = storage_path('images\gym_5.jpg');
        $image_gym_6 = storage_path('images\gym_6.jpg');
        $gym_1 = Gym::find(1);
        $gym_1
            ->addMedia($image_gym_1)
            ->preservingOriginal()
            ->toMediaCollection('gyms');
        $gym_2 = Gym::find(2);
        $gym_2
            ->addMedia($image_gym_2)
            ->preservingOriginal()
            ->toMediaCollection('gyms');
        $gym_3 = Gym::find(3);
        $gym_3
            ->addMedia($image_gym_3)
            ->preservingOriginal()
            ->toMediaCollection('gyms');
        $gym_4 = Gym::find(4);
        $gym_4
            ->addMedia($image_gym_4)
            ->preservingOriginal()
            ->toMediaCollection('gyms');
        $gym_5 = Gym::find(5);
        $gym_5
            ->addMedia($image_gym_5)
            ->preservingOriginal()
            ->toMediaCollection('gyms');
        $gym_6 = Gym::find(6);
        $gym_6
            ->addMedia($image_gym_6)
            ->preservingOriginal()
            ->toMediaCollection('gyms');
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
