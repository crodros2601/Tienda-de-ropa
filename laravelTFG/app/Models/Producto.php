<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Carrito;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'principalImg', 'img1', 'img2', 'categoria_id','activo'];

    public function carritos(){
        return $this->hasMany(Carrito::class);
    }

    public function categoria(){
        return $this->belongsTo(Categorias::class);
    }

    public function wishList(){
        return $this->hasMany(WishList::class);
    }

    public function tallas(){
        return $this->belongsToMany(Talla::class, 'producto_talla');
    }

    public function stocks() {
        return $this->hasMany(Stock::class);
    }

    public function oferta()
    {
        return $this->belongsTo(Oferta::class);
    }
    
}
