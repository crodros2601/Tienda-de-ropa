<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Talla;
use App\Models\Stock;

class StockSeeder extends Seeder
{
    public function run()
    {
        $productos = Producto::all();
        $tallas = Talla::all();

        foreach ($productos as $producto) {
            foreach ($tallas as $talla) {
                Stock::create([
                    'producto_id' => $producto->id,
                    'talla_id' => $talla->id,
                    'cantidad' => rand(10, 100)
                ]);
            }
        }
    }
}
