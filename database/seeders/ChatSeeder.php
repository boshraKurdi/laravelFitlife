<?php

namespace Database\Seeders;

use App\Models\Chat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Chat::create([
            'name' => '',
            'type' => 'private',
            'lastMessage' => 'hello'
        ]);
        Chat::create([
            'name' => '',
            'type' => 'private',
            'lastMessage' => 'hi'
        ]);
        Chat::create([
            'name' => '',
            'type' => 'private',
            'lastMessage' => 'bye'
        ]);
        Chat::create([
            'name' => 'group calores',
            'type' => 'public',
            'lastMessage' => 'no'
        ]);
        Chat::create([
            'name' => '',
            'type' => 'private',
            'lastMessage' => 'yes'
        ]);
    }
}
