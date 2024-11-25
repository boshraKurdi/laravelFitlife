<?php

namespace Database\Seeders;

use App\Models\PlanLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 9; $i++) {
            for ($j = 1; $j <= 3; $j++) {
                PlanLevel::create([
                    'plan_id' => $i,
                    'level_id' => $j
                ]);
            }
        }
        for ($i = 10; $i <= 13; $i++) {
            PlanLevel::create([
                'plan_id' => $i,
                'level_id' => 3
            ]);
        }
    }
}
