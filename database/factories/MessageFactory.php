<?php

namespace Database\Factories;

use App\Models\Chat;
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
        $chat = Chat::query()->inRandomOrder()->first();
        return [
            'text' => $this->faker->text,
            'chat_id' => $chat->id,
            'isCoach' => rand(0, 1),
            'isSeen' => 0
        ];
    }
}
