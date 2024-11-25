<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = ['Orange juice', 'Pizza', 'Shrimp Soup', 'Big Beef Burger', 'Hot Coffee Latte', 'Fresh Salad', 'Premium Steak'];
        $title_ar = ['شراب البرتقال', 'بيتزا', 'شوربة الروبيان', 'برجر لحم بقري كبير', 'لاتيه القهوة الساخنة', 'سلطة فريش', 'شريحة لحم'];
        $description = [
            'A drink rich in vitamin C and antioxidants, which produces a system and protects the body from diseases. It also helps improve heart health and digestion, as it provides contents from the Irish lands. In addition, in addition to promoting body health.',
            'Pizza is a popular Italian dish consisting of a thin dough base covered with sauce and cheese, and a variety of ingredients such as vegetables and meats can be added. It is baked in a hot oven until the base is crispy and the cheese is melted.',
            'Shrimp soup is a delicious dish consisting of a rich and flavorful broth, to which fresh shrimp and aromatic spices are added, giving it a delicious taste and a creamy texture. It is considered an ideal choice for seafood lovers.',
            'It is a delicious sandwich consisting of a large grilled beef patty, covered with melted cheese and fresh vegetables such as lettuce and tomatoes, served inside a soft burger bread with distinctive sauces that add a wonderful flavor.',
            'The ingredients combined are a coffee with milk or a latte, balanced and temptingly harmonious.',
            'Boil the sweet corn and leave it to cool. Prepare the rest of the ingredients (cucumber/tomato/bell pepper/onion/parsley). Add vegetable oil, lemon juice, salt, black pepper and mix well. Add the dressing to the ingredients and serve the salad.',
            'Premium steak is a high-quality cut of meat that is tender and rich in flavor, prepared from the finest cuts of beef and carefully grilled or cooked to achieve the perfect balance of juiciness and flavor. It is an ideal choice for meat lovers looking for a luxurious dining experience.'
        ];
        $description_ar = [
            'شراب البرتقال غني بفيتامين C ومضادات الأكسدة، مما يعزز جهاز المناعة ويحمي الجسم من الأمراض. كما يساعد في تحسين صحة القلب والهضم بفضل محتواه من الألياف والماء. بالإضافة إلى ذلك، يساهم في ترطيب الجسم وتعزيز صحة البشرة.',
            'البيتزا هي طبق إيطالي شهير يتكون من قاعدة رقيقة من العجين مغطاة بالصلصة والجبن، ويمكن إضافة مجموعة متنوعة من المكونات مثل الخضروات واللحوم. تُخبز في فرن ساخن حتى تصبح القاعدة مقرمشة والجبن ذائبًا. ',
            'حساء الروبيان هو طبق شهي يتكون من مرق غني ومليء بالنكهات، يُضاف إليه الروبيان الطازج والتوابل العطرية، مما يمنحه طعماً لذيذاً وقواماً كريميًا. يُعتبر خياراً مثالياً لمحبي المأكولات البحرية.',
            'هو ساندويتش لذيذ يتكون من شريحة لحم بقر كبيرة ومشوية، مُغطاة بالجبن الذائب والخضروات الطازجة مثل الخس والطماطم، وتُقدم داخل خبز برغر طري مع صلصات مميزة تضفي نكهة رائعة.',
            'المكونات مجتمعةً القهوة بالحليب أو اللاتيه، المتوازنة والمتجانسة بشكل مغري',
            'تعتبر السلطة الطازجة مصدرًا غنيًا بالفيتامينات والمعادن والألياف، مما يعزز صحة الجهاز الهضمي ويقوي المناعة. كما تساعد في تحسين صحة القلب وتقليل مخاطر الأمراض المزمنة بفضل محتواها المنخفض من السعرات الحرارية والدهون.',
            'ستيك بريميوم هو قطعة لحم عالية الجودة تتميز بطراوتها ونكهتها الغنية، تُحضر من أفضل قطع اللحم البقري وتُشوى أو تُطهى بعناية لتحقيق توازن مثالي بين العصارة والنكهة. يُعتبر خيارًا مثاليًا لعشاق اللحوم الذين يبحثون عن تجربة طعام فاخرة.'
        ];
        $components = ['Orange', 'Dough , Sauce , Cheese , Toppings: Meats , Vegetables', 'Shrimp , Shrimp Broth , Vegetables and Spices', 'Beef, zucchini, carrots, green pepper, broccoli, spinach, molokhia, bulgur, onions, eggs, garlic, herbs, salt and spices.', 'With one or two shots of espresso, steamed milk and a thin layer of foamed milk.', 'Lettuce, tomato, mushroom, carrot, sweet corn, black olives, cocktail sauce', 'Halal frozen beef, water, salt, onion, garlic, spices, carbohydrates, phosphates (E450, E451, E452), protein.'];
        $components_ar = ['برتقال', 'العجين , الصلصة , الجبن , الإضافات: اللحوم , الخضروات', 'الروبيان , مرق الروبيان,الخضار,لبهارات والتوابل', ' لحم بقري، كوسة، جزر، فلفل أخضر، بروكلي، سبانخ، ملوخية، برغل، بصل، بيض، ثوم، أعشاب، ملح وبهارات.', ' بجرعة أو جرعتين من الاسبريسو، حليب مبخر وتعلوهم طبقة رقيقة من الحليب الرغوي', 'خس ، طماطم ، فطر ، جزر ، ذرة حلوة ، زيتون أسود ، صلصة الكوكتيل', 'لحم بقري مجمد حلال، ماء، ملح، بصل، ثوم، بهارات، كربوهيدرات، فوسفات (E450، E451، E452)، بروتين.'];
        $prepare = [
            'j',
            'The oven temperature starts at a high temperature, usually around 200-220 degrees Celsius, Use pizza dough, fill it with sauce and cheese,Add your favorite toppings such as produce or flowers,Place the pizza in the oven for 10-15 minutes until it becomes a school headquarters and the cheese is golden.',
            'Heat a large skillet over medium heat and add 1 tablespoon olive oil. Add the cleaned shrimp shells, onion shells and carrots cut into large pieces to the skillet and stir for 5-10 minutes. Add cold water to cover the shells and vegetables and bring to a boil. Simmer for 20-30 minutes, then strain the stock to leave a clear, shrimp-flavored liquid.',
            'Mix minced chicken with salt, garlic powder and onion powder, then gradually add flour until the meat is firm. Divide into 8 balls, then flatten them into a circle, then place a piece of butter paper, then place a piece of burger meat, then a layer of butter paper and a piece of meat until the quantity is finished. From the burger meat recipe, you can store it until you use it. When cooking, brown it with a little oil.',
            'For hot frothy coffee: 2 tsp instant coffee, 2-3 tsp sugar, 2 tbsp water, fresh milk,For latte: 1 tbsp instant coffee, 1/2 tbsp sugar, 4 tbsp hot water, 1 cup microwaved milk (frothed),Use 4 tspn coffee powder,Use one tablespoon of hot coffee.',
            'Boil the sweet corn and leave it to cool. Prepare the rest of the ingredients (cucumber/tomato/bell pepper/onion/parsley). Add vegetable oil, lemon juice, salt, black pepper and mix well. Add the dressing to the ingredients and serve the salad.',
            'Arrange the meat pieces and season with salt and pepper. Grate the meat broth on the pieces to season them as well. Season each side. Heat the butter in a frying pan well and arrange the pieces in the pan to sauté and take on a color for exactly four minutes on each side without turning in the middle at all. From the recipe for fillet steak with mushrooms. Remove the meat in a bowl after cooking and add the mushrooms to the frying pan to sauté with the remaining butter and take on a color. Return the meat to the pan, then add the cooking cream (if we are going to add milk dissolved in flour, it is preferable to pour it first and stir well to mix it before adding the meat to avoid the flour clumping. The goal is to get a homogeneous sauce consistency).'
        ];
        $prepare_ar = [
            'j',
            'تبدأ درجة حرارة الفرن عند درجة حرارة عالية، وعادةً ما تكون حوالي 200-220 درجة مئوية
استخدمي عجينة البيتزا أملاها بالصلصة والجبن. 
قم بإضافة الحشوات التي تحبها مثل المنتجات أو الزهور.
 ضع البيتزا في الفرن لمدة تتراوح ما بين 10-15 دقيقة حتى تصبح مقرًا للمدرسة والجبن ذو لون ذهبي.',
            ' قم بتسخين مقلاة كبيرة على نار متوسطة وأضف ملعقة زيت زيتون و ضع قشور الروبيان المنظفة وقشور البصل والجزر المقطعة إلى القطع الكبيرة في المقلاة وقلبها لمدة 5-10 دقائق. و أضف الماء البارد لتغطية القشور والخضار واتركها تغلي ثم اترك المزيج يغلي على نيران هادئة لمدة 20-30 دقيقة، ثم قم بتصفية المرق للحصول على سائل شفاف وغني بنكهة الروبيان.',
            'يخلط دجاج مفروم مع الملح بودرة الثوم وبودة البصل ثم يضاف الدقيق بالتدريج لحتا يتماسك لحم
تقسم الي ٨ كرات ثمتفرد بشكل دائري ثم يوضع ورقه زبده ثم توضع قطعة لحم برجر ثم طبقه ورق زبده وقطعه لحم حتا انتها الكميه
من وصفة لحم البرجر ممكن تخزينه لحتى الاستعمال
عند الطبخ تحمر بقليل من الزيت',
            'للقهوة الرغوية الساخنة: 2 ملعقة صغيرة من القهوة سريعة الذوبان، 2-3 ملاعق صغيرة من السكر، 2 ملعقة كبيرة من الماء، حليب طازج. للاتيه: 1 ملعقة كبيرة من القهوة سريعة الذوبان، 1/2 ملعقة كبيرة من السكر، 4 ملاعق كبيرة من الماء الساخن، 1 كوب من الحليب المسخن في الميكروويف (رغوي)، استخدم 4 ملاعق صغيرة من مسحوق القهوة، استخدم ملعقة كبيرة من القهوة الساخنة.',
            'نسلق الذرة الحلوة و نتركها حتي تبرد و نحضر باقي المكونات (خيار / طماطم / فلفل رومي / بصل / بقدونس )
نضيف زيت نباتي ، عصير ليمون ، ملح ، فلفل اسود و نخلط جيدا
نضيف الدريسنج علي المكونات و نقدم السلطة',
            'ترص قطع اللحم و تتبل بالملح و الفلفل و نبشر مرقة اللحم علي القطع لنتبلها بها ايضا و يتم التتبيل علي كل وجهة و تسخن الزبدة فى طاسة الطهى جيدا و ترص القطع بالطاسة لتشوح و تأخد لون بالظبط مدة اربع دقايق لكل وجه دون تقليب فى النص ابدا و  من وصفة فيليه ستيك بالمشروم
ترفع اللحم فى اناء بعد السواء و يضاف المشروم فى طاسة التسوية ليشوح بباقي الزبدة و يأخذ لون
نعيد اللحم للطاسة قم نضيف كريمة الطهي (اذا سنضيف اللبن المذاب به دقيق يفضل صبه الاول مع التقليب الجيد ليتجانس قبل اضافة اللحم لتجنب كلكعة الدقيق و الهدف هو الحصول علي قوام صوص متجانس)',




        ];
        $category_id = [3, 2, 1, 1, 4, 5, 1];
        for ($i = 0; $i <= 6; $i++) {
            Meal::create([
                'title' => $title[$i],
                'title_ar' => $title_ar[$i],
                'description' => $description[$i],
                'description_ar' => $description_ar[$i],
                'components' => $components[$i],
                'components_ar' => $components_ar[$i],
                'prepare' => $prepare[$i],
                'calories' => rand(10, 100),
                'prepare_ar' => $prepare_ar[$i],
                'category_id' => $category_id[$i]
            ]);
        }
    }
}
