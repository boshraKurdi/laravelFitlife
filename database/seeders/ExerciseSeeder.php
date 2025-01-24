<?php

namespace Database\Seeders;

use App\Models\Exercise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = [
            'Squats',
            'Dumbbell Curls',
            'leg lift',
            'Side steps',
            'Front extensions',
            'thigh stretch',
            'Knee raise on bar',
            'One leg abdominal press exercise',
            'Side panel',
            'Russian exercises',
        ];
        $title_ar = [
            'السكوات',
            'الانحناءات بالدمبل',
            'رفع الساق',
            'الخطوات الجانبية',
            'التمديدات الأمامية',
            'التمدد الفخذ',
            'رفع الركبة على البار',
            'تمرين ضغط البطن بساق واحدة',
            'اللوح الجانبي',
            'التمارين الروسية',

        ];
        $description = [
            'Squats help make your core stronger by strengthening your abdominal muscles, which in turn makes simple physical activities like twisting, bending, and even standing easier. They may help you balance better and improve your posture.',
            "Dumbbell curls have many benefits including strengthening your back and core muscles, improving posture and stability, reducing your risk of injury, and enhancing your body's flexibility and mobility.",
            'It is a great exercise to strengthen the lower abdominal muscles and pelvic floor muscles.',
            'Excellent exercise to strengthen the gluteal muscles, lateral ligaments and improve the strength of the sides of the body.',
            'A great exercise to stretch the muscles in the front of the body, it is useful for improving muscle flexibility and increasing range of motion.',
            'A great exercise to stretch the thigh and hip muscles, it is useful for increasing muscle flexibility and improving range of motion.',
            'Excellent exercise to strengthen the abdominal and buttock muscles and improve the strength of the hip area.',
            'Excellent exercise to strengthen the abdominal muscles and improve overall strength and stability of the body.',
            'An effective exercise to strengthen the lateral and core muscles, it helps improve stability and overall body balance.',
            'Also known as Russian blocks or Russian bread, it is a popular exercise that uses heavy blocks to develop strength and fitness.'
        ];
        $description_ar = [
            'يساعد تمرين السكوات في جعل محور أو جذع الجسم أقوى من خلال تقوية عضلات البطن، وهذا بدوره يجعل من الأنشطة البدنية البسيطة، مثل: الالتفاف، والانحناء وحتى الوقوف أسهل. قد يساعد في جعلك تتوازن بطريقة أفضل ويحسن من وضعية جسمك.',
            'تمرين الانحناءات بالدمبل له فوائد عديدة تتضمن تقوية عضلات الظهر والجذع، تحسين الاستقامة والثبات، تقليل خطر الإصابات، وتعزيز مرونة الجسم والحركة',
            'هو تمرين رائع لتقوية عضلات البطن السفلية وعضلات الحوض',
            'تمرين ممتاز لتقوية عضلات الأرداف والأربطة الجانبية وتحسين قوة الجانبين من الجسم',
            'تمرين رائع لتمديد عضلات الجزء الأمامي من الجسم، وهو مفيد لتحسين مرونة العضلات وزيادة نطاق الحركة',
            'تمرين رائع لتمديد عضلات الفخذ والورك، وهو مفيد لزيادة مرونة العضلات وتحسين نطاق الحركة.',
            'تمرين ممتاز لتقوية عضلات البطن والأرداف وتحسين قوة منطقة الوركين',
            'تمرين ممتاز لتقوية عضلات البطن وتحسين القوة العامة واستقرار الجسم',
            'تمرين فعال لتقوية عضلات الجانبية والجذع، وهو يساعد على تحسين الثبات والتوازن العام للجسم. ',
            'المعروفة أيضًا بتمارين الكتل الروسية أو الخبز الروسية، هي تمارين رياضية شهيرة تستخدم الكتل الثقيلة لتنمية القوة واللياقة البدنية'
        ];
        $type = ['feminine', 'feminine', 'feminine', 'feminine', 'feminine', 'male', 'male', 'male', 'male', 'male'];

        for ($i = 0; $i < 10; $i++) {
            Exercise::create([
                'title' => $title[$i],
                'title_ar' => $title_ar[$i],
                'description' => $description[$i],
                'description_ar' => $description_ar[$i],
                'duration' =>  rand(10, 15),
                'counter' => 25,
                'type' => $type[$i],
                'calories' => rand(10, 100)
            ]);
        }
    }
}
