<?php

namespace Database\Factories;

use App\Models\Gym;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GymSection>
 */
class GymSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gym_id' => Gym::inRandomOrder()->first()->id,
            'section_id' =>  Section::inRandomOrder()->first()->id,
            'price' => rand('100', '10000')
        ];
    }
}
