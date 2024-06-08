<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class TallaController extends Controller
{
    public function getTallasPorProducto($productoId)
    {
        $producto = Producto::with('tallas')->findOrFail($productoId);
        return response()->json($producto->tallas);
    }
}
