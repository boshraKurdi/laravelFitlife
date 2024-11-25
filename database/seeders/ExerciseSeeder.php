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
            'Use this exercise to strengthen your back and shoulders at the same time: Stand straight with dumbbells in both hands (you can also drop them if you want). Raise your arms up so that the dumbbells are facing your shoulders, then lean forward and bring your hands back and hold this position for 30 seconds.',
            'Slowly raise your body up onto your toes, then slowly return to the starting position. You will feel a stretch in the back of your lower legs. Keep your back and knees straight as you perform the calf raise exercise. If you have difficulty maintaining your balance, use a chair or bar for extra stability.',
            'Stand up straight, with your feet shoulder-width apart. Leave your arms at your sides, squat down, bend your knees, push your right foot out to the side, and allow your arms to rise in front of you. When your thighs are parallel to the floor, stand up by pushing with your left foot to return to the starting position.',
            'Sit on the floor with your front knee at a 90-degree angle. Your thigh should be straight out in front of you while your leg is bent vertically. Then, instead of extending your back leg straight out, position it in a similar fashion to the front leg, bent at a 90-degree angle at the knee. This opens up your hips on both sides.',
            'Lie on the floor next to a prominent wall or door frame with your left leg against the wall. Lift your left leg and rest your left heel on the wall. Keep your left knee slightly bent. Gently straighten your left leg until you feel a stretch along the back of your left thigh.',
            'Have someone place the weights on your thighs about 5 cm from your knees and hold them there. This will be the starting position. Rise up on your toes as high as you can and squeeze your calves as you exhale. After holding this position for a second, slowly return to the starting position.',
            'Lie on your back with your knees bent (top illustration). Keep your back in a neutral position, not arched or stuck to the floor. Avoid tilting your hips, and tighten your abdominal muscles. Lift your right leg off the floor until your knee and hip are bent at a 90-degree angle.',
            'Lie on your right side with your legs straight and feet on top of each other. Place your right elbow under your right shoulder, point your forearm away and raise your hand into a fist so that the little finger side of your hand should be touching the floor. Place your neck in a neutral position and exhale to support your heart.',
            'This exercise targets all of your abdominal muscles, making it a great exercise for your obliques. To do this exercise, sit on the floor, then lean back at a 45-degree angle and pull your belly in. Then bend your knees, then lift your feet off the floor.'

        ];
        $description_ar = [
            'يساعد تمرين السكوات في جعل محور أو جذع الجسم أقوى من خلال تقوية عضلات البطن، وهذا بدوره يجعل من الأنشطة البدنية البسيطة، مثل: الالتفاف، والانحناء وحتى الوقوف أسهل. قد يساعد في جعلك تتوازن بطريقة أفضل ويحسن من وضعية جسمك.',
            'استعيني بهذا التمرين لتقوية عضلات الظهر والكتفين في آنٍ واحد: قفي بشكل مستقيم مع الإمساك بالدمبل في كلتا اليدين ويُمكن التخلي عنها بحسب ما هو متاح. ارفعي الذراعين نحو الأعلى بحيث تكون الدمبل مقابلة للكتفين، ثم انحني نحو الأمام وأرجعي اليدين للخلف واستمري على هذه الوضعية 30 ثانية.',
            'وارفع جسمك ببطء لتقف على أصابع القدمين، ثم عد ببطء إلى موضع البدء. ستشعر بشد في عضلات الجهة الخلفية من أسفل الساقين. أبقَ ظهرك وركبتيك مستقيمان عند أداء تمرين بطة الساق برفع الجسم. إذا وجدت صعوبة في الحفاظ على توازنك، فاستخدم كرسيًا أو عمودًا لمزيد من الثبات.',
            'قف باستقامة، وباعد بين قدميك بمقدار عرض الكتفين، اترك ذراعيك جانبًا، وانزل في وضع القرفصاء، واثنِ ركبتيك ادفع بقدمك اليمنى إلى الجانب، واسمح لذراعيك بالارتفاع أمامك ،عندما تصبح فخذيك موازية للأرض، قف عن طريق الدفع بقدمك اليسرى للعودة لوضع البداية',
            'اجلس على الأرض، واجعل ركبتك الأمامية بزاوية 90 درجة. يجب أن يكون فخذك مستقيمًا أمامك بينما تكون ساقك منحنية بشكل عمودي. ثم، بدلاً من مد ساقك الخلفية بشكل مستقيم، ضعها بطريقة مماثلة للساق الأمامية، مثنية بزاوية 90 درجة عند الركبة. يعمل هذا على فتح وركيك من كلا الجانبين.',
            'استلقِ على الأرض بجانب الركن البارز من الحائط أو إطار الباب بحيث تكون ساقك اليسرى ملاصقة للحائط. ارفع الساق اليسرى واسند العَقِب الأيسر على الحائط. أبقِ الركبة اليسرى مثنية قليلاً. افرد الساق اليسرى برفق إلى أن تشعر بشد بطول الجزء الخلفي من الفخذ الأيسر.',
            'أطلب من شخص ما وضع الأثقال على فخذيك على بعد 5 سم من ركبتيك وابقيها على هذا الوضع. هذه ستكون وضعية البدء. قم بالارتفاع بواسطة أصابع قدميك قدر الإمكان واضغط علي عضلات القدم الخلفية (السمانة) أثناء إخراج النفس (زفير). بعد الثبات على هذا الوضع لمدة ثانية عد لوضع البداية ببطء',
            'استلق على ظهرك مع ثني ركبتيك (الرسم التوضيحي العلوي). وأبقِ ظهرك في وضع محايد، غير مقوّس وغير ملتصق بالأرض. وتجنب إمالة الوركين، مع شد عضلات البطن. ارفع ساقك اليمنى بعيدًا عن الأرض حتى تثني ركبتك ووركك بزاوية قدرها 90 درجة.',
            'الاستلقاء على الجانب الأيمن مع الحرص على استقامة الرجلين والقدمين فوق بعضهما البعض. وضع الكوع الأيمن تحت الكتف الأيمن، وتوجيه الساعد بعيدًا مع رفع اليد بقبضة بحيث يجب أن يكون جانب الخنصر من اليد ملامسًا للأرض. وضع الرقبة في وضع محايد وإخراج الزفير لدعم القلب',
            'يستهدف هذا التمرين جميع عضلات البطن ، مما يجعله تمرينًا رائعًا وخصوصاَ لعضلات البطن الجانبية obliques. للقيام بالتمرين اجلس على الأرض ثم ارجع للخلف بزاوية 45 درجة وادخل بطنك الى الداخل. بعد ذلك اثني ركبتيك ، ثم قم برفع قدميك عن الأرض'
        ];

        for ($i = 0; $i < 10; $i++) {
            Exercise::create([
                'title' => $title[$i],
                'title_ar' => $title_ar[$i],
                'description' => $description[$i],
                'description_ar' => $description_ar[$i],
                'duration' =>  rand(10, 15),
                'counter' => 25,
                'calories' => rand(10, 100)
            ]);
        }
    }
}
