<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        Role::create(['name' => 'admin']);
        Role::create(['name' => 'coach']);

        $user = User::find(1);
        $user->assignRole('admin');
        for ($i = 2; $i < 6; $i++) {
            $coach = User::find($i);
            $coach->assignRole('coach');
        }
    }
}
