<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'width' => rand(40, 150),
            'height' => rand(150, 195),
            'gender' => 'woman',
            'lat' => 36.18553835,
            'lon' => 37.120130659655516,
            'address' => 'حلب حي الحمدانية',
            'illness' => '',
            'age' => 30,
            'description' => "A professional who specializes in improving the physical fitness and overall health of individuals. I design customized training programs that suit each person's needs, and provide the necessary guidance and support to achieve athletic goals. In addition, the trainer seeks to enhance motivation and commitment to a healthy lifestyle by providing nutritional and motivational advice.",
            'description_ar' => 'محترف متخصص في تحسين مستوى اللياقة البدنية والصحة العامة للأفراد. اقوم بتصميم برامج تدريبية مخصصة تتناسب مع احتياجات كل شخص، واقدم التوجيه والدعم اللازمين لتحقيق الأهداف الرياضية. بالإضافة إلى ذلك، اسعى المدرب إلى تعزيز الدافعية والالتزام بأسلوب حياة صحي من خلال تقديم نصائح غذائية وتحفيزية.',
            'analysis' => rand(1, 100),
            'communication' => rand(1, 100),
            'education' => rand(1, 100),
            'development' => rand(1, 100),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
