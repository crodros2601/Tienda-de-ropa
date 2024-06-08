<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductUpdateRequest;
use App\Models\Producto;
use App\Models\Talla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UserStoreRequest;
use App\Models\Stock;
use Carbon\Carbon;

class ProductosControllers extends Controller
{
    public function index()
{
    $now = Carbon::now();
    $productos = Producto::with(['oferta'])->get()->map(function ($producto) use ($now) {
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
    public function mostrar($id)
    {
    $producto = Producto::with('oferta')->findOrFail($id);

    if ($producto->oferta && now()->between(new \DateTime($producto->oferta->inicio), new \DateTime($producto->oferta->fin))) {
        $producto->precio_con_descuento = round($producto->precio * (1 - $producto->oferta->descuento / 100), 2);
    }

    return response()->json($producto);
    }

    public function añadir(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'principalImg' => 'nullable|image',
            'img1' => 'nullable|image',
            'categoria_id' => 'required|exists:categorias,id'
        ]);
        
        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->principalImg = $request->principalImg ? $request->principalImg->store('productos', 'public') : null;
        $producto->img1 = $request->img1 ? $request->img1->store('productos', 'public') : null;
        $producto->categoria_id = $request->categoria_id;
        $producto->save();

        $tallas = Talla::all(); 
        foreach ($tallas as $talla) {
            $stock = new Stock();
            $stock->producto_id = $producto->id;
            $stock->talla_id = $talla->id;
            $stock->cantidad = 1; 
            $stock->save();
        }
        
        $defaultSizes = Talla::all();
        foreach ($defaultSizes as $size) {
            $producto->tallas()->attach($size->id); 
        }

        return response()->json($producto, 201);
    }

    public function eliminar($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $producto->delete();

        return response()->json(['message' => 'Producto eliminado con éxito'], 200);
    }
   
    public function actualizar(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
    
        $data = $request->all();
    
        if ($request->hasFile('principalImg')) {
            $data['principalImg'] = $request->file('principalImg')->store('productos', 'public');
        }
    
        if ($request->hasFile('img1')) {
            $data['img1'] = $request->file('img1')->store('productos', 'public');
        }
    
        $producto->update($data);
    
        return response()->json($producto);
    }
    

    public function productosRecomendados($id)
    {
        $productoActual = Producto::findOrFail($id);
        $productosRecomendados = Producto::where('categoria_id', $productoActual->categoria_id)
                                        ->where('id', '!=', $id)
                                        ->where('activo',true)
                                        ->inRandomOrder()
                                        ->limit(100)
                                        ->get();

        return response()->json($productosRecomendados);
    }

        public function activar($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->activo = true;
            $producto->save();

            return response()->json(['message' => 'Producto activado con éxito'], 200);
        } catch (\Exception $e) {
            Log::error("Error al activar el producto: {$e->getMessage()}");
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function desactivar($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->activo = false;
            $producto->save();

            return response()->json(['message' => 'Producto desactivado con éxito'], 200);
        } catch (\Exception $e) {
            Log::error("Error al desactivar el producto: {$e->getMessage()}");
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    } 

    public function countActiveProducts()
    {
        $count = Producto::where('activo', true)->count();
        return response()->json(['active_products_count' => $count]);
    }
}   
