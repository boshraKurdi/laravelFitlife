<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lat = [36.18553835, 36.1999284, 36.2299807, 36.20945895];
        $lon = [37.120130659655516, 37.0980947, 37.144195, 37.141705498148916];
        $address = ['حلب حي الحمدانية', 'حلب حي حلب الجديدة', 'حلب حي الاشرفية', 'حلب شارع اسكندرون'];
        for ($i = 0; $i <= 3; $i++) {
            Location::query()->create([
                'lat'  => $lat[$i],
                'lon' => $lon[$i],
                'address' => $address[$i],
            ]);
        }
    }
}
