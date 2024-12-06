<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $array = [2, 3, 4, 5, 6, 7, 8, 9, 10];
        Group::create([
            'chat_id' => 1,
            'user_id' => 1
        ]);
        Group::create([
            'chat_id' => 1,
            'user_id' => 2
        ]);
        Group::create([
            'chat_id' => 2,
            'user_id' => 11
        ]);
        Group::create([
            'chat_id' => 2,
            'user_id' => 3
        ]);
        Group::create([
            'chat_id' => 3,
            'user_id' => 12
        ]);
        Group::create([
            'chat_id' => 3,
            'user_id' => 4
        ]);
        Group::create([
            'chat_id' => 4,
            'user_id' => 2
        ]);
        Group::create([
            'chat_id' => 4,
            'user_id' => 12
        ]);
        Group::create([
            'chat_id' => 4,
            'user_id' => 13
        ]);
        Group::create([
            'chat_id' => 5,
            'user_id' => 14
        ]);
        Group::create([
            'chat_id' => 5,
            'user_id' => 5
        ]);
    }
}
