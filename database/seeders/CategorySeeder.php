<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = ['Meat', 'Pizza', 'Drink', 'Coffee', 'Healthy'];
        $title_ar = ['لحمة', 'بيتزا', 'مشروبات', 'قهوة', 'غذاء صحي'];
        for ($i = 0; $i <= 4; $i++) {
            Category::create([
                'title' => $title[$i],
                'title_ar' => $title_ar[$i]
            ]);
        }
    }
}
