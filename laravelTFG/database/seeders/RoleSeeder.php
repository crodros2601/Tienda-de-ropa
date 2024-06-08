<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'fecha_nacimiento' => '2001-01-26',
            'telefono' => '123456789'
        ]);

        $directorRole = Role::create(['name' => 'director']);
        $directorUser = User::create([
            'name' => 'Director User',
            'email' => 'director@example.com',
            'password' => bcrypt('password'),
            'fecha_nacimiento' => '2001-01-26',
            'telefono' => '123456789'
        ]);

        $adminUser->assignRole($adminRole);
        $directorUser->assignRole($directorRole);
    }
}
