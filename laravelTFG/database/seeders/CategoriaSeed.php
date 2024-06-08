<?php

namespace Database\Seeders;

use App\Models\Categorias;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias = [
            ['genero' => 'Hombre', 'nombre' => 'Americanas'],
            ['genero' => 'Hombre', 'nombre' => 'Camisas'],
            ['genero' => 'Hombre', 'nombre' => 'Polos'],
            ['genero' => 'Hombre', 'nombre' => 'Pantalones'],
            ['genero' => 'Hombre', 'nombre' => 'Sudaderas'],
            ['genero' => 'Hombre', 'nombre' => 'Chaquetas'],
            ['genero' => 'Hombre', 'nombre' => 'Bañadores'],
            ['genero' => 'Mujer', 'nombre' => 'Abrigos'],
            ['genero' => 'Mujer', 'nombre' => 'Pantalones'],
            ['genero' => 'Mujer', 'nombre' => 'Chalecos'],
            ['genero' => 'Mujer', 'nombre' => 'Blusas y camisas'],
            ['genero' => 'Mujer', 'nombre' => 'Tops'],
            ['genero' => 'Mujer', 'nombre' => 'Bikinis'],
            ['genero' => 'Mujer', 'nombre' => 'Faldas'],
            ['genero' => 'Mujer', 'nombre' => 'Vestidos'],
            ['genero' => 'Nina', 'nombre' => 'Vestidos'],
            ['genero' => 'Nina', 'nombre' => 'Camisas y blusas'],
            ['genero' => 'Nina', 'nombre' => 'Abrigos'],
            ['genero' => 'Nina', 'nombre' => 'Pantalones'],
            ['genero' => 'Nina', 'nombre' => 'Faldas'],
            ['genero' => 'Nina', 'nombre' => 'Bikinis'],
            ['genero' => 'Nino', 'nombre' => 'Camisas'],
            ['genero' => 'Nino', 'nombre' => 'Sudaderas'],
            ['genero' => 'Nino', 'nombre' => 'Jerséis'],
            ['genero' => 'Nino', 'nombre' => 'Pantalones'],
            ['genero' => 'Nino', 'nombre' => 'Abrigos'],
            ['genero' => 'Nino', 'nombre' => 'Bañadores'],
            ['genero' => 'Chica', 'nombre' => 'Abrigos'],
            ['genero' => 'Chica', 'nombre' => 'Blusas y camisas'],
            ['genero' => 'Chica', 'nombre' => 'Pantalones'],
            ['genero' => 'Chica', 'nombre' => 'Vestidos'],
            ['genero' => 'Chica', 'nombre' => 'Chalecos'],
            ['genero' => 'Chica', 'nombre' => 'Faldas'],
            ['genero' => 'Chica', 'nombre' => 'Bikinis'],
            ['genero' => 'Chica', 'nombre' => 'Tops'],
            ['genero' => 'Chico', 'nombre' => 'Camisas'],
            ['genero' => 'Chico', 'nombre' => 'Sudaderas'],
            ['genero' => 'Chico', 'nombre' => 'Chalecos'],
            ['genero' => 'Chico', 'nombre' => 'Pantalones'],
            ['genero' => 'Chico', 'nombre' => 'Bañadores'],
            ['nombre' => 'Atuendo casual', 'estacion' => 'Primavera'],
            ['nombre' => 'Vestido', 'estacion' => 'Primavera'],
            ['nombre' => 'Accesorios', 'estacion' => 'Primavera'],
            ['nombre' => 'Trajes de baño', 'estacion' => 'Verano'],
            ['nombre' => 'Ropa deportiva', 'estacion' => 'Verano'],
            ['nombre' => 'Accesorios', 'estacion' => 'Verano'],
            ['nombre' => 'Prendas de punto', 'estacion' => 'Otoño'],
            ['nombre' => 'Prendas de entretiempo', 'estacion' => 'Otoño'],
            ['nombre' => 'Calzado de temporada', 'estacion' => 'Otoño'],
            ['nombre' => 'Abrigos', 'estacion' => 'Invierno'],
            ['nombre' => 'Calzado', 'estacion' => 'Invierno'],
            ['nombre' => 'Accesorios', 'estacion' => 'Invierno']
        ];

        foreach ($categorias as $categoria){
            Categorias::create($categoria);
        }
    }
}
