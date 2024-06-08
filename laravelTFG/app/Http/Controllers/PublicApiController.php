<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categorias;
use App\Models\Oferta;
use App\Models\Talla;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicApiController extends Controller
{
    public function getAllProductos(Request $request)
    {
        $now = Carbon::now();
        $productosQuery = Producto::with(['oferta'])
            ->where('activo', true);

        if ($request->has('nombre')) {
            $productosQuery->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->has('con_stock')) {
            $productosQuery->whereHas('stocks', function($query) {
                $query->where('cantidad', '>', 0);
            });
        }

        $productos = $productosQuery->get();

        $productos = $productos->map(function ($producto) use ($now) {
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
    }

    public function getProducto($id)
    {
        $producto = Producto::with('oferta')->findOrFail($id);

        if ($producto->oferta && now()->between(new \DateTime($producto->oferta->inicio), new \DateTime($producto->oferta->fin))) {
            $producto->precio_con_descuento = round($producto->precio * (1 - $producto->oferta->descuento / 100), 2);
        }

        return response()->json($producto);
    }

    public function getProductosByCategoria($categoriaId)
    {
        $productos = Producto::where('categoria_id', $categoriaId)->where('activo', true)->get();
        return response()->json($productos);
    }

    public function getProductosByTalla()
    {
        $tallas = Talla::all();

        return response()->json($tallas);
    }

    public function getAllCategorias()
    {
        $categorias = Categorias::all();
        return response()->json($categorias);
    }

    public function getAllOfertas()
    {
        $ofertas = Oferta::all();
        return response()->json($ofertas);
    }

    public function getOferta($id)
    {
        $oferta = Oferta::findOrFail($id);
        return response()->json($oferta);
    }

    public function getTallaById($tallaId)
    {
        $talla = Talla::findOrFail($tallaId);
        return response()->json($talla);
    }
    
    public function getTallaByNombre($nombre)
    {
        $talla = Talla::where('nombre', $nombre)->first();
        return response()->json($talla);
    }

    public function getProductosByNombre($nombre)
    {
        $producto = Producto::where('nombre', $nombre)->first();
        return response()->json($producto);
    }

    public function getProductosByPrecio($precio)
    {
        $producto = Producto::where('precio', $precio)->first();
        return response()->json($producto);
    }
}
