<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talla extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];


    public function productos(){
        return $this->belongsToMany(Producto::class, 'producto_talla');
    }

    public function carritos(){
        return $this->hasMany(Carrito::class);
    }
    
    public function stocks() {
        return $this->hasMany(Stock::class);
    }
}
