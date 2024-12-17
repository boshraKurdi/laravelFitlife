<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Goal;
use App\Models\Gym;
use App\Models\Meal;
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
        $video = storage_path('videos\video.mp4');
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
        $image_exe_1 = storage_path('images\exe_1.png');
        $image_exe_2 = storage_path('images\exe_2.png');
        $image_exe_3 = storage_path('images\exe_3.png');
        $image_exe_4 = storage_path('images\exe_4.jpg');
        $image_exe_5 = storage_path('images\exe_5.jpeg');
        $image_exe_6 = storage_path('images\exe_6.jpg');
        $image_exe_7 = storage_path('images\exe_7.jpg');
        $image_exe_8 = storage_path('images\exe_8.png');
        $image_exe_9 = storage_path('images\exe_9.jpg');
        $image_exe_10 = storage_path('images\exe_10.webp');
        $exercise_1 = Exercise::find(1);
        $exercise_1
            ->addMedia($image_exe_1)
            ->preservingOriginal()
            ->toMediaCollection('exercises');
        $exercise_2 = Exercise::find(2);
        $exercise_2
            ->addMedia($image_exe_2)
            ->preservingOriginal()
            ->toMediaCollection('exercises');
        $exercise_3 = Exercise::find(3);
        $exercise_3
            ->addMedia($image_exe_3)
            ->preservingOriginal()
            ->toMediaCollection('exercises');
        $exercise_4 = Exercise::find(4);
        $exercise_4
            ->addMedia($image_exe_4)
            ->preservingOriginal()
            ->toMediaCollection('exercises');
        $exercise_5 = Exercise::find(5);
        $exercise_5
            ->addMedia($image_exe_5)
            ->preservingOriginal()
            ->toMediaCollection('exercises');
        $exercise_6 = Exercise::find(6);
        $exercise_6
            ->addMedia($image_exe_6)
            ->preservingOriginal()
            ->toMediaCollection('exercises');
        $exercise_7 = Exercise::find(7);
        $exercise_7
            ->addMedia($image_exe_7)
            ->preservingOriginal()
            ->toMediaCollection('exercises');
        $exercise_8 = Exercise::find(8);
        $exercise_8
            ->addMedia($image_exe_8)
            ->preservingOriginal()
            ->toMediaCollection('exercises');
        $exercise_9 = Exercise::find(9);
        $exercise_9
            ->addMedia($image_exe_9)
            ->preservingOriginal()
            ->toMediaCollection('exercises');
        $exercise_10 = Exercise::find(10);
        $exercise_10
            ->addMedia($image_exe_10)
            ->preservingOriginal()
            ->toMediaCollection('exercises');
        for ($i = 1; $i <= 10; $i++) {
            $exercise_v = Exercise::find($i);
            $exercise_v
                ->addMedia($video)
                ->preservingOriginal()
                ->toMediaCollection('exercises');
        }
        $image_coach_1 = storage_path('images\coach_1.jpg');
        $image_coach_2 = storage_path('images\coach_2.jpg');
        $image_coach_3 = storage_path('images\coach_3.jpg');
        $image_coach_4 = storage_path('images\coach_4.jpg');
        $image_coach_5 = storage_path('images\coach_5.jpg');

        $coach_1 = User::find(2);
        $coach_1
            ->addMedia($image_coach_1)
            ->preservingOriginal()
            ->toMediaCollection('users');
        $coach_2 = User::find(2);
        $coach_2
            ->addMedia($image_coach_2)
            ->preservingOriginal()
            ->toMediaCollection('users');
        $coach_3 = User::find(3);
        $coach_3
            ->addMedia($image_coach_3)
            ->preservingOriginal()
            ->toMediaCollection('users');
        $coach_4 = User::find(4);
        $coach_4
            ->addMedia($image_coach_4)
            ->preservingOriginal()
            ->toMediaCollection('users');
        $coach_5 = User::find(5);
        $coach_5
            ->addMedia($image_coach_5)
            ->preservingOriginal()
            ->toMediaCollection('users');

        $image_meal_1 = storage_path('images\meal_1.jpg');
        $image_meal_2 = storage_path('images\meal_2.jpg');
        $image_meal_4 = storage_path('images\meal_3.jpg');
        $image_meal_3 = storage_path('images\meal_4.jpg');
        $image_meal_5 = storage_path('images\meal_5.jpg');
        $image_meal_6 = storage_path('images\meal_6.jpg');
        $image_meal_7 = storage_path('images\meal_7.jpg');
        $image_meal_8 = storage_path('images\meal_8.png');
        $image_meal_9 = storage_path('images\meal_9.png');
        $image_meal_10 = storage_path('images\meal_10.png');
        $image_meal_11 = storage_path('images\meal_11.png');
        $image_meal_12 = storage_path('images\meal_12.png');
        $image_meal_13 = storage_path('images\meal_13.png');
        $meal_1 = Meal::find(1);
        $meal_1
            ->addMedia($image_meal_1)
            ->preservingOriginal()
            ->toMediaCollection('meals');
        $meal_2 = Meal::find(2);
        $meal_2
            ->addMedia($image_meal_2)
            ->preservingOriginal()
            ->toMediaCollection('meals');
        $meal_3 = Meal::find(3);
        $meal_3
            ->addMedia($image_meal_3)
            ->preservingOriginal()
            ->toMediaCollection('meals');
        $meal_4 = Meal::find(4);
        $meal_4
            ->addMedia($image_meal_4)
            ->preservingOriginal()
            ->toMediaCollection('meals');
        $meal_5 = Meal::find(5);
        $meal_5
            ->addMedia($image_meal_5)
            ->preservingOriginal()
            ->toMediaCollection('meals');
        $meal_6 = Meal::find(6);
        $meal_6
            ->addMedia($image_meal_6)
            ->preservingOriginal()
            ->toMediaCollection('meals');
        $meal_7 = Meal::find(7);
        $meal_7
            ->addMedia($image_meal_7)
            ->preservingOriginal()
            ->toMediaCollection('meals');
        $meal_8 = Meal::find(8);
        $meal_8
            ->addMedia($image_meal_8)
            ->preservingOriginal()
            ->toMediaCollection('meals');
        $meal_9 = Meal::find(9);
        $meal_9
            ->addMedia($image_meal_9)
            ->preservingOriginal()
            ->toMediaCollection('meals');
        $meal_10 = Meal::find(10);
        $meal_10
            ->addMedia($image_meal_10)
            ->preservingOriginal()
            ->toMediaCollection('meals');
        $meal_11 = Meal::find(11);
        $meal_11
            ->addMedia($image_meal_11)
            ->preservingOriginal()
            ->toMediaCollection('meals');
        $meal_12 = Meal::find(12);
        $meal_12
            ->addMedia($image_meal_12)
            ->preservingOriginal()
            ->toMediaCollection('meals');
        $meal_13 = Meal::find(13);
        $meal_13
            ->addMedia($image_meal_13)
            ->preservingOriginal()
            ->toMediaCollection('meals');
    }
}
