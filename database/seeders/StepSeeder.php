<?php

namespace Database\Seeders;

use App\Models\Step;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $content = [
            'Stand straight with your feet shoulder-width apart or slightly wider, and hold the dumbbells in front of your chest or up on your heels.',
            'Hold the dumbbells in your hands in a balanced manner, then move your palms away from your back.',
            'Begin the movement by slowly bending your knees, as if you were getting ready to sit in a chair behind you. Make sure your knees do not extend past your toes.',
            'Continue lowering until your thighs are parallel to the floor, making sure to keep your back straight.',
            'Look forward and make sure your knees do not bend inward, and keep your weight on your heels and the tops of your feet.',
            'Stand straight with dumbbells in each hand, or you can drop them if you want.',
            'Raise your arms up so that the dumbbells are in line with your shoulders.',
            'Lean forward and bring your arms back and hold for 30 seconds.',
            'Slowly raise your body to stand on your toes, then slowly return to the starting position.',
            'You will feel a stretch in the muscles on the back of your lower legs.',
            'Keep your back and knees straight when performing calf raises.',
            'If you have difficulty maintaining your balance, use a chair or bar for extra stability',
            'Stand up straight, with your feet shoulder-width apart.',
            'Leave your arms out to the side, and squat down',
            'Bend your knees, push your right foot out to the side, and allow your arms to rise in front of you.',
            'When your thighs are parallel to the floor, stand up by pushing your left foot back to the starting position',
            'Sit on the floor, with your front knee at a 90-degree angle.',
            'Your thigh should be straight out in front of you while your leg is bent vertically.',
            'Then, instead of extending your back leg straight out, position it in a similar way to the front leg, bent at a 90-degree angle at the knee.',
            'This opens up your hips on both sides',
            'Lie on the floor next to a prominent corner of a wall or door frame with your left leg against the wall.',
            'Lift your left leg and rest your left heel on the wall.',
            'Keep your left knee slightly bent.',
            'Gently straighten your left leg until you feel a stretch along the back of your left thigh',
            'Have someone place the weights on your thighs 5 cm from your knees and hold them there.',
            'Rise up on your toes as high as you can and squeeze your calves as you exhale.',
            'After holding this position for a second, slowly return to the starting position.',
            'Lie on your back with your knees bent and your back in a neutral position, not arched or stuck to the floor.',
            'Avoid tilting your hips, and tighten your abdominal muscles.',
            'Lift your right leg off the floor until your knee and hip are bent at a 90-degree angle.',
            'Lie on your right side with your legs straight and feet on top of each other.',
            'Place your right elbow under your right shoulder, point your forearm away and raise your hand into a fist so that the little finger side of your hand should be touching the floor.',
            'Place your neck in a neutral position and exhale to support your heart.',
            'This exercise targets all of your abdominal muscles, making it a great exercise for your obliques.',
            'To do the exercise, sit on the floor, then lean back at a 45-degree angle and pull your belly in.',
            'Then bend your knees, then lift your feet off the floor.'
        ];
        $content_ar = [
            'وقف مستقيمًا بقدميك على عرض الكتفين أو أوسع قليلاً، وامكني الدمبل أمام صدرك أو ارتفع بعقبيك.',
            'ابتعدي الدمبل بين يديك بشكل متوازن، ثم ابتعدي كفيك في اتجاه ظهرك.',
            'إبدأي الحركة بالانحناء في ركبتيك ببطء، كما لو كنت تستعد لجلوسك على كرسي خلفك. تأكدي من أن ركبتيك لا تتجاوز مواجهة إصبعي قدميك.',
            'استمري في النزول حتى تكون فخذيك متوازنة مع الأرض، وتأكدي من الحفاظ على ظهرك مستقيم.',
            'انظري إلى الأمام وتأكدي من أن ركبتيك لا تنحني إلى الداخل، وحافظي على وزنك على كعبيك وأعلى القدمين.',
            'قفي بشكل مستقيم مع الإمساك بالدمبل في كلتا اليدين ويُمكن التخلي عنها بحسب ما هو متاح.',
            ' ارفعي الذراعين نحو الأعلى بحيث تكون الدمبل مقابلة للكتفين.',
            'انحني نحو الأمام وأرجعي اليدين للخلف واستمري على هذه الوضعية 30 ثانية',
            'ارفع جسمك ببطء لتقف على أصابع القدمين، ثم عد ببطء إلى موضع البدء.',
            'ستشعر بشد في عضلات الجهة الخلفية من أسفل الساقين.',
            ' أبقَ ظهرك وركبتيك مستقيمان عند أداء تمرين بطة الساق برفع الجسم.',
            ' إذا وجدت صعوبة في الحفاظ على توازنك، فاستخدم كرسيًا أو عمودًا لمزيد من الثبات',
            '  قف باستقامة، وباعد بين قدميك بمقدار عرض الكتفين.',
            ' اترك ذراعيك جانبًا، وانزل في وضع القرفصاء.',
            ' اثنِ ركبتيك ادفع بقدمك اليمنى إلى الجانب، واسمح لذراعيك بالارتفاع أمامك .',
            'عندما تصبح فخذيك موازية للأرض، قف عن طريق الدفع بقدمك اليسرى للعودة لوضع البداية',
            ' اجلس على الأرض، واجعل ركبتك الأمامية بزاوية 90 درجة. ',
            'يجب أن يكون فخذك مستقيمًا أمامك بينما تكون ساقك منحنية بشكل عمودي.',
            ' ثم، بدلاً من مد ساقك الخلفية بشكل مستقيم، ضعها بطريقة مماثلة للساق الأمامية، مثنية بزاوية 90 درجة عند الركبة.',
            ' يعمل هذا على فتح وركيك من كلا الجانبين',
            'استلقِ على الأرض بجانب الركن البارز من الحائط أو إطار الباب بحيث تكون ساقك اليسرى ملاصقة للحائط.',
            'ارفع الساق اليسرى واسند العَقِب الأيسر على الحائط. ',
            'أبقِ الركبة اليسرى مثنية قليلاً.',
            'افرد الساق اليسرى برفق إلى أن تشعر بشد بطول الجزء الخلفي من الفخذ الأيسر',
            'أطلب من شخص ما وضع الأثقال على فخذيك على بعد 5 سم من ركبتيك وابقيها على هذا الوضع.',
            'قم بالارتفاع بواسطة أصابع قدميك قدر الإمكان واضغط علي عضلات القدم الخلفية (السمانة) أثناء إخراج النفس (زفير).',
            ' بعد الثبات على هذا الوضع لمدة ثانية عد لوضع البداية ببطء',
            ' استلق على ظهرك مع ثني ركبتيك  وأبقِ ظهرك في وضع محايد، غير مقوّس وغير ملتصق بالأرض.',
            'تجنب إمالة الوركين، مع شد عضلات البطن. ',
            'ارفع ساقك اليمنى بعيدًا عن الأرض حتى تثني ركبتك ووركك بزاوية قدرها 90 درجة.',
            'الاستلقاء على الجانب الأيمن مع الحرص على استقامة الرجلين والقدمين فوق بعضهما البعض.',
            'وضع الكوع الأيمن تحت الكتف الأيمن، وتوجيه الساعد بعيدًا مع رفع اليد بقبضة بحيث يجب أن يكون جانب الخنصر من اليد ملامسًا للأرض.',
            ' وضع الرقبة في وضع محايد وإخراج الزفير لدعم القلب',
            'يستهدف هذا التمرين جميع عضلات البطن ، مما يجعله تمرينًا رائعًا وخصوصاَ لعضلات البطن الجانبية obliques.',
            ' للقيام بالتمرين اجلس على الأرض ثم ارجع للخلف بزاوية 45 درجة وادخل بطنك الى الداخل.',
            ' بعد ذلك اثني ركبتيك ، ثم قم برفع قدميك عن الأرض'

        ];
        $exe = [1, 1, 1, 1, 1, 2, 2, 2, 3, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 6, 6, 6, 6, 7, 7, 7, 8, 8, 8, 9, 9, 9, 10, 10, 10];
        for ($i = 0; $i < count($content); $i++) {
            Step::create([
                'content' => $content[$i],
                'content_ar' => $content_ar[$i],
                'exercise_id' => $exe[$i]
            ]);
        }
    }
}
