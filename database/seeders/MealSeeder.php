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
        $title = [
            'Orange juice',
            'Pizza',
            'Shrimp Soup',
            'Big Beef Burger',
            'Hot Coffee Latte',
            'Fresh Salad',
            'Premium Steak',
            'Fresh Chicken Veggies',
            'Grilled Chicken',
            'Panner Noodles',
            'Chicken Noodles',
            'Bread Boiled Egg',
            'Immunity Dish',

        ];
        $title_ar = [
            'شراب البرتقال',
            'بيتزا',
            'شوربة الروبيان',
            'برجر لحم بقري كبير',
            'لاتيه القهوة الساخنة',
            'سلطة فريش',
            'شريحة لحم',
            'خضروات دجاج طازجة',
            'دجاج مشوي',
            'معكرونة بانر',
            'نودلز الدجاج',
            'خبز بيض مسلوق',
            'طبق المناعة'
        ];
        $description = [
            'A drink rich in vitamin C and antioxidants, which produces a system and protects the body from diseases. It also helps improve heart health and digestion, as it provides contents from the Irish lands. In addition, in addition to promoting body health.',
            'Pizza is a popular Italian dish consisting of a thin dough base covered with sauce and cheese, and a variety of ingredients such as vegetables and meats can be added. It is baked in a hot oven until the base is crispy and the cheese is melted.',
            'Shrimp soup is a delicious dish consisting of a rich and flavorful broth, to which fresh shrimp and aromatic spices are added, giving it a delicious taste and a creamy texture. It is considered an ideal choice for seafood lovers.',
            'It is a delicious sandwich consisting of a large grilled beef patty, covered with melted cheese and fresh vegetables such as lettuce and tomatoes, served inside a soft burger bread with distinctive sauces that add a wonderful flavor.',
            'The ingredients combined are a coffee with milk or a latte, balanced and temptingly harmonious.',
            'Boil the sweet corn and leave it to cool. Prepare the rest of the ingredients (cucumber/tomato/bell pepper/onion/parsley). Add vegetable oil, lemon juice, salt, black pepper and mix well. Add the dressing to the ingredients and serve the salad.',
            'Premium steak is a high-quality cut of meat that is tender and rich in flavor, prepared from the finest cuts of beef and carefully grilled or cooked to achieve the perfect balance of juiciness and flavor. It is an ideal choice for meat lovers looking for a luxurious dining experience.',
            'It is a nutritious and delicious meal that contains many important nutrients that benefit human health.',
            'Grilled chicken is a rich source of high-quality proteins, vitamins and minerals, which promote muscle building and overall body health. It is also low in fat compared to other cooking methods, which helps maintain a healthy weight and improve energy levels.',
            'Paneer pasta is a good nutritional choice that can contribute to a healthy diet when eaten in moderation and with healthy ingredients.',
            'Chicken noodles are a good source of protein, which helps build muscle and promote recovery after exercise. They also contain vitamins and minerals that boost immune health and help improve digestion.',
            'Boiled egg toast can be part of a balanced diet that supports physical and mental health.',
            "The immune system boosts your body's health by providing essential nutrients that strengthen your immune system, such as vitamins, minerals, and antioxidants. Eating this dish regularly helps reduce your risk of disease and boost your ability to fight off infections."
        ];
        $description_ar = [
            'شراب البرتقال غني بفيتامين C ومضادات الأكسدة، مما يعزز جهاز المناعة ويحمي الجسم من الأمراض. كما يساعد في تحسين صحة القلب والهضم بفضل محتواه من الألياف والماء. بالإضافة إلى ذلك، يساهم في ترطيب الجسم وتعزيز صحة البشرة.',
            'البيتزا هي طبق إيطالي شهير يتكون من قاعدة رقيقة من العجين مغطاة بالصلصة والجبن، ويمكن إضافة مجموعة متنوعة من المكونات مثل الخضروات واللحوم. تُخبز في فرن ساخن حتى تصبح القاعدة مقرمشة والجبن ذائبًا. ',
            'حساء الروبيان هو طبق شهي يتكون من مرق غني ومليء بالنكهات، يُضاف إليه الروبيان الطازج والتوابل العطرية، مما يمنحه طعماً لذيذاً وقواماً كريميًا. يُعتبر خياراً مثالياً لمحبي المأكولات البحرية.',
            'هو ساندويتش لذيذ يتكون من شريحة لحم بقر كبيرة ومشوية، مُغطاة بالجبن الذائب والخضروات الطازجة مثل الخس والطماطم، وتُقدم داخل خبز برغر طري مع صلصات مميزة تضفي نكهة رائعة.',
            'المكونات مجتمعةً القهوة بالحليب أو اللاتيه، المتوازنة والمتجانسة بشكل مغري',
            'تعتبر السلطة الطازجة مصدرًا غنيًا بالفيتامينات والمعادن والألياف، مما يعزز صحة الجهاز الهضمي ويقوي المناعة. كما تساعد في تحسين صحة القلب وتقليل مخاطر الأمراض المزمنة بفضل محتواها المنخفض من السعرات الحرارية والدهون.',
            'ستيك بريميوم هو قطعة لحم عالية الجودة تتميز بطراوتها ونكهتها الغنية، تُحضر من أفضل قطع اللحم البقري وتُشوى أو تُطهى بعناية لتحقيق توازن مثالي بين العصارة والنكهة. يُعتبر خيارًا مثاليًا لعشاق اللحوم الذين يبحثون عن تجربة طعام فاخرة.',
            ' تعد وجبة مغذية ولذيذة تحتوي على العديد من العناصر الغذائية الهامة التي تعود بالفوائد على صحة الإنسان.',
            'يعتبر الدجاج المشوي مصدرًا غنيًا بالبروتينات عالية الجودة والفيتامينات والمعادن، مما يعزز بناء العضلات وصحة الجسم العامة. كما أنه قليل الدهون مقارنة بالطرق الأخرى للطهي، مما يساعد في الحفاظ على وزن صحي وتحسين مستويات الطاقة.',
            'معكرونة بانر خيارًا غذائيًا جيدًا يمكن أن يُساهم في نظام غذائي صحي عند تناولها بشكل معتدل ومع مكونات صحية.',
            'نودلز الدجاج تعتبر مصدرًا جيدًا للبروتين، مما يساعد في بناء العضلات وتعزيز الشفاء بعد التمارين. كما أنها تحتوي على الفيتامينات والمعادن التي تعزز من صحة الجهاز المناعي وتساهم في تحسين الهضم.',
            'خبز البيض المسلوق يمكن أن يكون جزءًا من نظام غذائي متوازن يدعم الصحة الجسدية والعقلية.',
            'طبق المناعة يعزز صحة الجسم من خلال توفير العناصر الغذائية الأساسية التي تقوي الجهاز المناعي، مثل الفيتامينات والمعادن ومضادات الأكسدة. يساعد تناول هذا الطبق بانتظام في تقليل خطر الإصابة بالأمراض وتعزيز القدرة على مواجهة العدوى.',
        ];
        $components = [
            'Orange',
            'Dough , Sauce , Cheese , Toppings: Meats , Vegetables',
            'Shrimp , Shrimp Broth , Vegetables and Spices',
            'Beef, zucchini, carrots, green pepper, broccoli, spinach, molokhia, bulgur, onions, eggs, garlic, herbs, salt and spices.',
            'With one or two shots of espresso, steamed milk and a thin layer of foamed milk.',
            'Lettuce, tomato, mushroom, carrot, sweet corn, black olives, cocktail sauce',
            'Halal frozen beef, water, salt, onion, garlic, spices, carbohydrates, phosphates (E450, E451, E452), protein.',
            'Fresh chicken, Assorted vegetables: such as onions, garlic, green peppers, red peppers, eggplant, zucchini, carrots, coriander, Olive oil, Spices and seasonings: such as salt, black pepper, garlic powder, cumin seeds, dried coriander, or any other spices that add a delicious flavor to the meal, Lemon juice: can be added to add a refreshing flavor.',
            '1 whole chicken (about 1.5 - 2 kg), 4 tablespoons olive oil, juice of 1 lemon, 4 cloves minced garlic, 1 teaspoon paprika, 1 teaspoon cumin, 1 teaspoon dried coriander, salt and pepper to taste',
            '250 grams of pasta (such as spaghetti, fusilli or any type you prefer), 2 tablespoons of olive oil, 3 cloves of garlic, minced, 1 medium onion, minced, 1 bell pepper (any color) cut into cubes, 1 cup of chopped tomatoes (can use canned tomatoes), 1 teaspoon of dried herbs (such as oregano or basil), grated Parmesan cheese (optional for serving)',
            '200g rice noodles or regular noodles, 250g chicken breast, sliced, 2 cups mixed vegetables (such as carrots, peppers, broccoli), 3 tablespoons soy sauce and 1 teaspoon sesame oil',
            '2 boiled eggs, 2 slices of bread (wholemeal bread can be used), salt and pepper to taste, sliced ​​cucumber or tomato (optional), 1 teaspoon mayonnaise or yogurt (optional)',
            '1 cup fresh spinach, 1/2 avocado, 1 chopped cucumber, 1 tablespoon lemon juice, 1 handful of nuts (such as almonds or walnuts)'
        ];
        $components_ar = [
            'برتقال',
            'العجين , الصلصة , الجبن , الإضافات: اللحوم , الخضروات',
            'الروبيان , مرق الروبيان,الخضار,لبهارات والتوابل',
            ' لحم بقري، كوسة، جزر، فلفل أخضر، بروكلي، سبانخ، ملوخية، برغل، بصل، بيض، ثوم، أعشاب، ملح وبهارات.',
            ' بجرعة أو جرعتين من الاسبريسو، حليب مبخر وتعلوهم طبقة رقيقة من الحليب الرغوي',
            'خس ، طماطم ، فطر ، جزر ، ذرة حلوة ، زيتون أسود ، صلصة الكوكتيل',
            'لحم بقري مجمد حلال، ماء، ملح، بصل، ثوم، بهارات، كربوهيدرات، فوسفات (E450، E451، E452)، بروتين.',
            'دجاج طازج ، خضروات متنوعة: مثل البصل، الثوم، الفلفل الأخضر، الفلفل الأحمر، الباذنجان، الكوسا، الجزر، الكزبرة، زيت الزيتون ، التوابل والبهارات: مثل الملح، الفلفل الأسود، بودرة الثوم، بذور الكمون، الكزبرة المجففة، أو أي توابل أخرى تضيف نكهة لذيذة للوجبة ، عصير الليمون: يمكن إضافته لإضافة نكهة منعشة.',
            'دجاجة كاملة (حوالي 1.5 - 2 كجم)، 4 ملاعق كبيرة زيت زيتون ،عصير ليمونة واحدة ،4 فصوص ثوم مفروم ، ملعقة صغيرة من البابريكا، ملعقة صغيرة من الكمون ، ملعقة صغيرة من الكزبرة المجففة ، ملح وفلفل حسب الذوق',
            '250 غرام من المعكرونة (مثل السباغيتي أو الفوسيلي أو أي نوع تفضله) ، 2 ملعقة كبيرة من زيت الزيتون ، 3 فصوص ثوم مفرومة ،1 حبة بصل متوسطة مفرومة ،1 حبة فلفل رومي (أي لون) مقطعة إلى مكعبات ،1 كوب من الطماطم المقطعة (يمكن استخدام الطماطم المعلبة) ،1 ملعقة صغيرة من الأعشاب المجففة (مثل الأوريجانو أو الريحان) ،جبنة بارميزان مبشورة (اختياري للتقديم)',
            '200 غرام من نودلز الأرز أو النودلز العادية ،250 غرام من صدور الدجاج، مقطعة إلى شرائح ، 2 كوب من الخضار المشكّلة (مثل الجزر والفلفل والبروكلي) ،3 ملاعق كبيرة من صوص الصويا وملعقة صغيرة من زيت السمسم',
            '2 بيضة مسلوقة ، شريحتان من الخبز (يمكن استخدام الخبز الكامل) ،ملح وفلفل حسب الذوق ،شرائح خيار أو طماطم (اختياري) ، ملعقة صغيرة من المايونيز أو الزبادي (اختياري)',
            'كوب من السبانخ الطازجة ، نصف حبة أفوكادو ،حبة خيار مقطعة ، ملعقة كبيرة من عصير الليمون، حفنة من المكسرات (مثل اللوز أو الجوز)'
        ];
        $prepare = [
            'j',
            'The oven temperature starts at a high temperature, usually around 200-220 degrees Celsius, Use pizza dough, fill it with sauce and cheese,Add your favorite toppings such as produce or flowers,Place the pizza in the oven for 10-15 minutes until it becomes a school headquarters and the cheese is golden.',
            'Heat a large skillet over medium heat and add 1 tablespoon olive oil. Add the cleaned shrimp shells, onion shells and carrots cut into large pieces to the skillet and stir for 5-10 minutes. Add cold water to cover the shells and vegetables and bring to a boil. Simmer for 20-30 minutes, then strain the stock to leave a clear, shrimp-flavored liquid.',
            'Mix minced chicken with salt, garlic powder and onion powder, then gradually add flour until the meat is firm. Divide into 8 balls, then flatten them into a circle, then place a piece of butter paper, then place a piece of burger meat, then a layer of butter paper and a piece of meat until the quantity is finished. From the burger meat recipe, you can store it until you use it. When cooking, brown it with a little oil.',
            'For hot frothy coffee: 2 tsp instant coffee, 2-3 tsp sugar, 2 tbsp water, fresh milk,For latte: 1 tbsp instant coffee, 1/2 tbsp sugar, 4 tbsp hot water, 1 cup microwaved milk (frothed),Use 4 tspn coffee powder,Use one tablespoon of hot coffee.',
            'Boil the sweet corn and leave it to cool. Prepare the rest of the ingredients (cucumber/tomato/bell pepper/onion/parsley). Add vegetable oil, lemon juice, salt, black pepper and mix well. Add the dressing to the ingredients and serve the salad.',
            'Arrange the meat pieces and season with salt and pepper. Grate the meat broth on the pieces to season them as well. Season each side. Heat the butter in a frying pan well and arrange the pieces in the pan to sauté and take on a color for exactly four minutes on each side without turning in the middle at all. From the recipe for fillet steak with mushrooms. Remove the meat in a bowl after cooking and add the mushrooms to the frying pan to sauté with the remaining butter and take on a color. Return the meat to the pan, then add the cooking cream (if we are going to add milk dissolved in flour, it is preferable to pour it first and stir well to mix it before adding the meat to avoid the flour clumping. The goal is to get a homogeneous sauce consistency).',
            'Wash the vegetables well and cut them into small pieces. Cut the chicken into strips or cubes, chop the onion and garlic, heat a frying pan over medium heat and add olive oil. Add the chicken pieces and stir until browned. Then add the onion and garlic and continue frying until the onion becomes translucent, Add the vegetables and spices: Add the chopped vegetables to the pan and stir them with the rest of the ingredients. Add your favorite spices such as salt, pepper, garlic powder, cumin seeds, or any other spices you prefer. Stir the ingredients well until the vegetables are cooked and the flavors are combined.',
            'In a bowl, combine olive oil, lemon juice, minced garlic, paprika, cumin, coriander, salt, and pepper. Add fresh herbs if desired. Place chicken in a large bowl or plastic bag and add marinade. Make sure the chicken is completely covered with marinade. Refrigerate chicken for at least 2 hours (preferably overnight) to allow flavors to infuse. Preheat grill or oven to medium-high (about 400°F). Roast chicken for 1.5 to 2 hours, or until golden and internal temperature reaches 165°F.',
            'In a large pot, bring enough water to the boil with a pinch of salt. Add the pasta and follow the cooking instructions on the package until al dente. Drain the pasta and set aside. In a large frying pan, heat the olive oil over medium heat. Add the onion and sauté until translucent. Add the minced garlic and sauté for a minute until fragrant. Add the bell pepper and cook for 3-4 minutes until softened. Add the chopped tomatoes and dried herbs. Simmer for 5-7 minutes until the sauce thickens. You can add a little water if the sauce is too thick. Add the cooked pasta to the pan with the sauce and stir well until the pasta is coated with the sauce.',
            'Boil the noodles according to the instructions on the package and drain. In a frying pan, heat the sesame oil and add the chicken strips until fully cooked. Add the vegetables to the pan and cook for 3-5 minutes until tender. Add the noodles and soy sauce and mix well. Serve hot.',
            'Boil the eggs for 10 minutes, then peel and cut into slices. Place the egg slices on a slice of bread, add salt and pepper, add cucumber or tomato slices and mayonnaise if desired, cover with the second slice of bread, then serve as a snack or breakfast.',
            'Wash the spinach well and then put it in a bowl, add the avocado, cucumber and nuts, pour lemon juice over the ingredients and mix well, serve the dish as a healthy salad that boosts immunity.'
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
            'اغسل الخضروات جيدًا واقطعها إلى قطع صغيرة. قطع الدجاج إلى شرائح أو مكعبات، وقم بتقطيع البصل والثوم ، سخن مقلاة على نار متوسطة وأضف زيت الزيتون. أضف قطع الدجاج وقلب حتى يحمر. ثم أضف البصل والثوم واستمر في القلي حتى يصبح البصل شفافًا ، إضافة الخضروات والتوابل: أضف الخضروات المقطعة إلى المقلاة وقلبها مع بقية المكونات. أضف التوابل المفضلة لديك مثل ملح، فلفل، بودرة الثوم، بذور الكمون، أو أي توابل أخرى تفضلها. قلب المكونات جيدًا حتى تنضج الخضروات ويتم امتزاج النكهات.',
            ' في وعاء، اخلط زيت الزيتون، عصير الليمون، الثوم المفروم، البابريكا، الكمون، الكزبرة، الملح، والفلفل. يمكنك إضافة الأعشاب الطازجة إذا رغبت ،ضع الدجاج في وعاء كبير أو كيس بلاستيكي وأضف التتبيلة. تأكد من تغطية الدجاج بالكامل بالتتبيلة ، اترك الدجاج في الثلاجة لمدة ساعتين على الأقل (يفضل تركه طوال الليل) ليتشرب النكهات ، سخن الشواية أو الفرن على درجة حرارة متوسطة إلى عالية (حوالي 200 درجة مئوية). ، اشوي الدجاج لمدة 1.5 إلى 2 ساعة، أو حتى يصبح لونه ذهبيًا ويصل درجة الحرارة الداخلية إلى 75 درجة مئوية.',
            'في قدر كبير، اغلي كمية كافية من الماء مع قليل من الملح. أضف المعكرونة واتبع تعليمات الطهي على العبوة حتى تنضج. ثم صفي المعكرونة واحتفظ بها جانبًا ، في مقلاة كبيرة، سخن زيت الزيتون على نار متوسطة. أضف البصل وقلّبه حتى يصبح شفافًا ،أضف الثوم المفروم وقلّبه لمدة دقيقة حتى تفوح رائحته ، ثم أضف الفلفل الرومي واطبخه لمدة 3-4 دقائق حتى يلين ، أضف الطماطم المقطعة والأعشاب المجففة، ثم اترك الخليط يغلي لمدة 5-7 دقائق حتى تتكثف الصلصة. يمكنك إضافة القليل من الماء إذا كانت الصلصة كثيفة جدًا ، أضف المعكرونة المطبوخة إلى المقلاة مع الصلصة وقلّب جيدًا حتى تتغلف المعكرونة بالصلصة.',
            'قم بسلق النودلز وفقًا للتعليمات على العبوة ثم صفيها ، في مقلاة، سخن زيت السمسم وأضف شرائح الدجاج حتى تنضج تمامًا ،أضف الخضار إلى المقلاة واطبخها لمدة 3-5 دقائق حتى تطرى ،أضف النودلز وصوص الصويا واخلط المكونات جيدًا، ثم قدّم الطبق ساخنًا',
            'قم بسلق البيض لمدة 10 دقائق ثم قشره وقطعه إلى شرائح ، ضع شرائح البيض على شريحة من الخبز، وأضف الملح والفلفل ، أضف شرائح الخيار أو الطماطم والمايونيز إذا رغبت ، غطِّ بشريحة الخبز الثانية، ثم قدمه كوجبة خفيفة أو إفطار.',
            'غسل السبانخ جيدًا ثم ضعها في وعاء ، أضف الأفوكادو والخيار والمكسرات ،اسكب عصير الليمون فوق المكونات وامزج جيدًا ،قدّم الطبق كسلطة صحية تعزز المناعة.'
        ];
        $category_id = [3, 2, 1, 1, 4, 5, 1, 1, 4, 1, 4, 4, 4];
        for ($i = 0; $i <= 12; $i++) {
            Meal::create([
                'title' => $title[$i],
                'title_ar' => $title_ar[$i],
                'description' => $description[$i],
                'description_ar' => $description_ar[$i],
                'components' => $components[$i],
                'components_ar' => $components_ar[$i],
                'prepare' => $prepare[$i],
                'calories' => rand(10, 100),
                'carbohydrates' => rand(10, 100),
                'fats' => rand(10, 100),
                'proteins' => rand(10, 100),
                'prepare_ar' => $prepare_ar[$i],
                'category_id' => $category_id[$i]
            ]);
        }
    }
}
