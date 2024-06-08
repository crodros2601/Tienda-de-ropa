<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Categorias;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(CategoriaSeed::class);
        $this->call(TallaSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(StockSeeder::class);

         \App\Models\User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
             'fecha_nacimiento' => '2001-01-26',
             'telefono' => '123456789'
         ]);
         $this->call(RoleSeeder::class);

    }
}
