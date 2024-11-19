<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $group = Group::query()->inRandomOrder()->first();
        return [
            'text' => $this->faker->text,
            'group_id' => $group->id,
            'isCoach' => rand(0, 1),
            'isSeen' => 0
        ];
    }
}
