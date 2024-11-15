<?php

namespace Database\Seeders;

use App\Models\Gym;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GymSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = [' نادي الاتحاد الرياضي', 'نادي الحرية الرياضي', 'نادي الجلاء الرياضي', 'نادي الكرامة الرياضي', 'نادي السلام الرياضي', 'نادي النصر الرياضي'];
        $description = ['Aut est qui ab et. Nihil et molestiae nam incidunt earum quibusdam. Rem quia magni repellendus exercitationem dolorum pariatur. Et non eaque explicabo mollitia.', 'Aut est qui ab et. Nihil et molestiae nam incidunt earum quibusdam. Rem quia magni repellendus exercitationem dolorum pariatur. Et non eaque explicabo mollitia.', 'Aut est qui ab et. Nihil et molestiae nam incidunt earum quibusdam. Rem quia magni repellendus exercitationem dolorum pariatur. Et non eaque explicabo mollitia.', 'Aut est qui ab et. Nihil et molestiae nam incidunt earum quibusdam. Rem quia magni repellendus exercitationem dolorum pariatur. Et non eaque explicabo mollitia.', 'Aut est qui ab et. Nihil et molestiae nam incidunt earum quibusdam. Rem quia magni repellendus exercitationem dolorum pariatur. Et non eaque explicabo mollitia.', 'Aut est qui ab et. Nihil et molestiae nam incidunt earum quibusdam. Rem quia magni repellendus exercitationem dolorum pariatur. Et non eaque explicabo mollitia.'];
        $open = ['09:00', '10:00', '10:00', '08:00', '09:00', '10:00'];
        $close = ['02:00', '03:00', '04:00', '05:00', '06:00', '07:00'];
        $type = ['mixed', 'mixed', 'For women', 'mixed', 'For women', 'For men'];
        for ($i = 0; $i < 6; $i++) {
            Gym::create([
                'name' => $title[$i],
                'description' => $description[$i],
                'open' => $open[$i],
                'close' => $close[$i],
                'type' => $type[$i],
                'location_id' => Location::find($i + 1)->id
            ]);
        }
    }
}
