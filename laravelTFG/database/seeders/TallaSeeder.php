<?php

namespace Database\Seeders;

use App\Models\Talla;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TallaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tallas = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'];

        foreach ($tallas as $nombre) {
            Talla::create(['nombre' => $nombre]);
        }
    }
}
