<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descuento', 'inicio', 'fin'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
