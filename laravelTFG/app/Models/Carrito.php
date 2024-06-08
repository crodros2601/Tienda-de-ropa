<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;

class Carrito extends Model
{

    protected $fillable = ['producto_id', 'user_id','talla_id','cantidadProducto', 'precio'];

    use HasFactory;

    public function producto()
    {
        return $this->belongsTo(Producto::class,'producto_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function talla() {
        return $this->belongsTo(Talla::class);
    }
}
