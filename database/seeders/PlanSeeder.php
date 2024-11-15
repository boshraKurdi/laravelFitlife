<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function PHPSTORM_META\type;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = ['thigh muscles', 'Abdominal muscles', 'flat stomach', 'Chest muscles', 'forearm muscles', 'Arm muscles', 'shoulder muscles', 'waistline', 'Strengthen and slim the thigh gap'];
        $title_ar = ['عضلات الفخذين', 'عضلات البطن ', 'بطن مسطحة', 'عضلات الصدر', 'عضلات الساعدين', 'عضلات الذراعين', 'عضلات الكتفين', 'محيط الخصر', 'تقوية وتنحيف فجوة الفخذين'];
        $description = [
            'This plan targets the thigh muscles in a variety of ways, which helps to strengthen the muscles and improve athletic performance. Don"t forget to warm up and stretch before and after the workout to help strengthen the thigh muscles and avoid injuries.',
            "This plan aims to strengthen the lower abdominal muscles and improve their strength and flexibility. Don't forget to include adequate rest periods between exercises to maintain the health of the abdominal muscles. It is also recommended to eat a healthy diet and maintain a focus on protein intake to support the muscle building process.",
            "This plan effectively targets the upper abdominal muscles, helping to strengthen them and improve their strength and appearance. Don't forget to warm up and stretch before and after the workout to avoid injuries and improve performance.",
            "This plan targets the chest muscles in a variety of ways, helping to strengthen them and improve their shape and strength. It is also recommended to include stretching and warm-up exercises for the chest muscles before starting the exercises to avoid injuries.",
            "This plan is designed to effectively strengthen and increase the strength of your forearm muscles. It is recommended that you follow the plan regularly and focus on the correct techniques for each exercise to get the best results.",
            "This plan targets the arm muscles comprehensively, helping to strengthen them and improve their strength and shape. It is also recommended to include warm-up and stretching exercises for the arm muscles before starting the exercises to avoid injuries.",
            "This plan aims to strengthen and develop the shoulder muscles in a comprehensive manner, which helps improve body alignment and increase strength and fitness in this area. It is also recommended to include stretching and warm-up exercises for the shoulder muscles before starting the exercises to prevent injuries.",
            "This plan aims to strengthen and slim the thigh gap area, which helps in achieving a slim and toned figure. Regular exercise and maintaining a healthy diet are recommended for best results.",
            "This plan aims to strengthen and slim the thigh gap area, which helps in achieving a slim and toned figure. Regular exercise and maintaining a healthy diet are recommended for best results."

        ];
        $description_ar = [
            'هذه الخطة تستهدف عضلات الفخذين بشكل متنوع، مما يساعد على تقوية العضلات وتحسين الأداء الرياضي. لا تنسى تمارين التسخين والتمديد قبل وبعد التمرين للمساعدة في تقوية عضلات الفخذين وتجنب الإصابات.',
            'هذه الخطة تهدف إلى تقوية عضلات البطن السفلية وتحسين قوتها ومرونتها. لا تنسى إدراج فترات راحة مناسبة بين التمارين للحفاظ على صحة عضلات البطن. كما يُنصح بتناول غذاء صحي والحفاظ على التركيز على تناول البروتين لدعم عملية بناء العضلات.',
            'هذه الخطة تستهدف عضلات البطن العلوية بفعالية، مما يساعد في تقويتها وتحسين قوتها وظهورها. لا تنسى تمارين التسخين والتمدد قبل وبعد التمرين لتجنب الإصابات وتحسين الأداء.',
            'هذه الخطة تستهدف عضلات الصدر بشكل متنوع، مما يساعد في تقويتها وتحسين شكلها وقوتها. يُنصح أيضًا بتضمين تمارين تمدد وتسخين لعضلات الصدر قبل بدء التمرينات لتجنب الإصابات.',
            'هذه الخطة مصممة لتقوية عضلات الساعدين بفعالية وزيادة قوتهما. يُنصح أن تتبع الخطة بانتظام وبتركيز على التقنيات الصحيحة لكل تمرين للحصول على أفضل النتائج.',
            'هذه الخطة تستهدف عضلات الذراعين بشكل شامل، مما يساعد في تقويتها وتحسين قوتهما وشكلهما. يُنصح أيضًا بتضمين تمارين تسخين وتمدد لعضلات الذراعين قبل بدء التمرينات لتجنب الإصابات.',
            'هذه الخطة تهدف إلى تقوية وتنمية عضلات الكتفين بشكل شامل، مما يساعد على تحسين استقامة الجسم وزيادة القوة واللياقة في هذه المنطقة. يُنصح أيضًا بتضمين تمارين تمدد وتسخين لعضلات الكتفين قبل بدء التمرينات لمنع الإصابات.',
            'هذه الخطة تهدف إلى تقوية وتنحيف منطقة فجوة الفخذين، مما يساعد في تحقيق الشكل الممشوق والمشدود. يُنصح بممارسة الرياضة بانتظام والحفاظ على نظام غذائي صحي للحصول على أفضل النتائج.',
            'هذه الخطة تهدف إلى تقوية وتنحيف منطقة فجوة الفخذين، مما يساعد في تحقيق الشكل الممشوق والمشدود. يُنصح بممارسة الرياضة بانتظام والحفاظ على نظام غذائي صحي للحصول على أفضل النتائج.'
        ];
        $type = ['thigh exercises', 'Abdominal exercises', 'Abdominal exercises', 'Stretching exercises', 'Stretching exercises', 'Stretching exercises', 'Stretching exercises', 'Sculpting exercises', 'thigh exercises'];
        $type_ar = ['تمارين الفخذ', 'تمارين البطن', 'تمارين البطن', 'تمارين الشد', 'تمارين الشد', 'تمارين الشد', 'تمارين الشد', 'تمارين النحت', 'تمارين الفخذ',];
        $duration = [5, 5, 5, 5, 5, 5, 5, 5, 5];
        $muscle = ['thigh', 'Upper abdomen', 'lower abdomen', 'chest', 'forearm', 'arm', 'shoulder', 'Waist', 'thigh'];
        $muscle_ar = ['فخذين', 'البطن', 'البطن', 'الصدر', 'الساعدين', 'الذراعين', 'الكتفين', 'الخصر', 'فخذين'];
        for ($i = 0; $i < 9; $i++) {
            Plan::create([
                'title' => $title[$i],
                'title_ar' => $title_ar[$i],
                'description' => $description[$i],
                'description_ar' => $description_ar[$i],
                'duration' => $duration[$i],
                'type' => $type[$i],
                'type_ar' => $type_ar[$i],
                'muscle' => $muscle[$i],
                'muscle_ar' => $muscle_ar[$i]
            ]);
        }
    }
}
