<?php

namespace Database\Seeders;

use App\Models\Goal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = ['Burn fat', 'building muscle', 'improve physical fitness', 'Improve athletic performance'];
        $description = [
            'This program aims to burn excess body fat through cardio exercises (such as running, swimming, or cycling) and strength training. The program also includes a balanced diet containing proteins, vegetables, and whole grains.',
            'This goal focuses on increasing muscle mass through resistance exercises such as weight lifting. The program requires additional calories from sources rich in protein and healthy carbohydrates to promote muscle growth.',
            'This program aims to improve overall fitness through a variety of exercises such as yoga, Pilates, and endurance exercises. This helps in burning fat and improving strength and flexibility.',
            'This goal focuses on increasing body weight by improving athletic performance. This involves advanced training and increasing calories with an emphasis on protein and carbohydrates to boost energy and performance.'
        ];
        $description_ar = [
            'يهدف هذا البرنامج إلى حرق الدهون الزائدة في الجسم من خلال ممارسة تمارين الكارديو (مثل الجري، السباحة، أو ركوب الدراجة) وتمارين القوة. يتضمن البرنامج أيضًا نظامًا غذائيًا متوازنًا يحتوي على البروتينات والخضروات والحبوب الكاملة.',
            'يركز هذا الهدف على زيادة الكتلة العضلية من خلال تمارين المقاومة مثل رفع الأثقال. يتطلب البرنامج تناول سعرات حرارية إضافية من مصادر غنية بالبروتين والكربوهيدرات الصحية لتعزيز نمو العضلات.',
            'يهدف هذا البرنامج إلى تحسين اللياقة البدنية العامة من خلال مجموعة متنوعة من التمارين مثل اليوغا، البيلاتس، وتمارين التحمل. يساعد هذا في حرق الدهون وتحسين القوة والمرونة.',
            '  يركز هذا الهدف على زيادة وزن الجسم من خلال تحسين الأداء الرياضي. يتضمن ذلك تدريبات متقدمة وزيادة السعرات الحرارية مع التركيز على البروتينات والكربوهيدرات لتعزيز الطاقة والأداء.'
        ];
        $title_ar = ['حرق الدهون', 'بناء العضلات', 'تحسين اللياقة البدنية', 'تحسين الأداء الرياضي'];
        $calories_max = [1000, 500, 600, 800];
        $calories_min = [500, 300, 400, 500];
        $duration = [2, 2, 3, 4];

        for ($i = 0; $i <= 3; $i++) {
            Goal::create([
                'title' => $title[$i],
                'title_ar' => $title_ar[$i],
                'description' => $description[$i],
                'description_ar' => $description_ar[$i],
                'calories_max' => $calories_max[$i],
                'calories_min' => $calories_min[$i],
                'duration' => $duration[$i]
            ]);
        }
    }
}
