<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    public static $num = 0;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $muscle = ['arm', 'pectoral', 'belly', 'thigh'];
        if (self::$num > 3) {
            self::$num = 0;
        }

        return [
            'title' => $this->faker->title,
            'description' => $this->faker->text,
            'duration' =>  rand(1, 3) . ' week',
            'muscle' => $muscle[self::$num++]
        ];
    }
}
