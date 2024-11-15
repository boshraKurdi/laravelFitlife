<?php

namespace Database\Seeders;

use App\Models\GymSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GymSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GymSection::factory(20)->create();
    }
}
