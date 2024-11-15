<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = ['Sports equipment', 'Strength exercises', 'Cardio', 'Yoga and Pilates Department', 'Dance or Zumba', 'Running and walking trails'];
        $title_ar = ['قسم الأجهزة الرياضية', 'قسم تمارين القوة', ' قسم الكارديو', 'قسم اليوغا والبيلاتس', 'قسم الزومبا أو الرقص', 'مسارات الجري والمشي'];
        $description = [
            'It contains a variety of devices such as treadmills, stationary bikes, and resistance devices (iron) to strengthen muscles.',
            'Focused on bodybuilding and weightlifting training, it features a range of free weights and strength machines.',
            'It includes machines such as treadmills, stationary bikes, and rowing machines, and aims to improve cardiorespiratory capacity.',
            'Offers classes to improve flexibility, balance and mental focus.',
            'Focuses on fun aerobic exercises that help burn calories.',
            'The club has outdoor spaces, and may include dedicated jogging and walking tracks.'
        ];
        $description_ar = [
            'يحتوي على مجموعة متنوعة من الأجهزة مثل أجهزة المشي، الدراجة الثابتة، وأجهزة المقاومة (الحديد) لتقوية العضلات.',
            'يركز على تدريبات كمال الأجسام ورفع الأثقال، ويضم مجموعة من الأوزان الحرة وآلات القوة.',
            'يشمل آلات مثل أجهزة المشي، الدراجات الثابتة، وأجهزة التجديف، وتهدف إلى تحسين القدرة القلبية التنفسية.',
            ' يقدم دروسًا لتحسين المرونة والتوازن والتركيز الذهني.',
            'يركز على التمارين الهوائية الممتعة التي تساعد في حرق السعرات الحرارية.',
            'النادي يحتوي على مساحات خارجية، فقد يتضمن مسارات مخصصة للجري والمشي.'
        ];
        for ($i = 0; $i < 6; $i++) {
            Section::create([
                'title' => $title[$i],
                'title_ar' => $title_ar[$i],
                'description' => $description[$i],
                'description_ar' => $description_ar[$i]
            ]);
        }
    }
}
