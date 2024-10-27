<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $array = [2, 3, 4, 5, 6, 7, 8, 9, 10];
        $user = User::query()->inRandomOrder()->whereNotIn('id', $array)->first()->id;
        return [
            'user_id' => $user,
            'coach_id' => User::query()->whereIn('id', $array)->inRandomOrder()->first()->id,
            'lastMessage' => 'hello'
        ];
    }
}
