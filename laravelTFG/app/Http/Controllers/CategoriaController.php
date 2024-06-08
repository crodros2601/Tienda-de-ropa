<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\Oferta;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categorias::all();
        return response()->json($categorias);
    }

    public function productosPorCategoria($categoriaId)
    {
        $productos = Producto::where('categoria_id', $categoriaId)->get();
        return response()->json($productos);
    }

    public function getCategoriaByProducto($id)
    {
        $producto = Producto::with('categoria')->findOrFail($id);
        return response()->json($producto->categoria ?? ['message' => 'Categoría no encontrada']);
        
    }

    public function productosPorNombreYGenero($nombre, $genero)
    {
        $categoria = Categorias::where('nombre', $nombre)->where('genero', $genero)->first();
    
        if ($categoria) {
            $productos = Producto::with('oferta') 
                ->where('categoria_id', $categoria->id)
                ->where('activo', true)
                ->get();
    
            $now = Carbon::now();
            $productos->transform(function ($producto) use ($now) {
                if ($producto->oferta) {
                    $inicio = new Carbon($producto->oferta->inicio);
                    $fin = new Carbon($producto->oferta->fin);
                    if ($now->greaterThan($fin) && $producto->oferta->estado !== 'expirada') {
                        $producto->oferta->estado = 'expirada';
                        $producto->oferta->save();
                        $producto->oferta_id = null;
                        $producto->save();
                    } elseif ($now->between($inicio, $fin)) {
                        $producto->precio_con_descuento = round($producto->precio * (1 - $producto->oferta->descuento / 100), 2);
                    }
                }
                return $producto;
            });
            return response()->json($productos);
        } else {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }
    }

    public function productosPorNombreYEstacion($nombre, $estacion)
    {
        $categoria = Categorias::where('nombre', $nombre)->where('estacion', $estacion)->first();

        if ($categoria) {
            $productos = Producto::with('oferta')
                ->where('categoria_id', $categoria->id)
                ->where('activo', true)
                ->get();

            $now = Carbon::now();
            $productos->transform(function ($producto) use ($now) {
                if ($producto->oferta) {
                    $inicio = new Carbon($producto->oferta->inicio);
                    $fin = new Carbon($producto->oferta->fin);
                    if ($now->greaterThan($fin) && $producto->oferta->estado !== 'expirada') {
                        $producto->oferta->estado = 'expirada';
                        $producto->oferta->save();
                        $producto->oferta_id = null;
                        $producto->save();
                    } elseif ($now->between($inicio, $fin)) {
                        $producto->precio_con_descuento = round($producto->precio * (1 - $producto->oferta->descuento / 100), 2);
                    }
                }
                return $producto;
            });
            return response()->json($productos);
        } else {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }
    }

}
