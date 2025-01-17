<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'service' => 'chat with coach',
            'duration' =>  1,
            'price' => 1000
        ]);
        Service::create([
            'service' => 'chat with coach',
            'duration' =>  2,
            'price' => 2000
        ]);
        Service::create([
            'service' => 'chat with coach',
            'duration' =>  3,
            'price' => 3000
        ]);
    }
}
