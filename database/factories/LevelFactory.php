<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class LevelFactory extends Factory
{
    public static $num = 0;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $level = ['weak', 'middle', 'strong'];
        if (self::$num > 2) {
            self::$num = 0;
        }
        return [
            'title' => $level[self::$num++],
        ];
    }
}
