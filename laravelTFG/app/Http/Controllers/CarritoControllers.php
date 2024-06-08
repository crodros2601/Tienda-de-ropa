<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Carrito;
use App\Models\Factura;
use App\Models\Stock;

class CarritoControllers extends Controller
{
    public function agregarProducto(Request $request)
{
    $request->validate([
        'user_id' => 'required|integer',
        'producto_id' => 'required|integer',
        'cantidad' => 'required|integer|min:1',
        'talla_id' => 'required|integer',
    ]);

    $producto = Producto::with('oferta')->find($request->producto_id);
    if (!$producto) {
        return response()->json(['message' => 'Producto no encontrado'], 404);
    }

    $precio = $producto->precio;
    if ($producto->oferta && now()->between(new \DateTime($producto->oferta->inicio), new \DateTime($producto->oferta->fin))) {
        $precio = round($producto->precio * (1 - $producto->oferta->descuento / 100), 2);
    }

    $stock = Stock::where('producto_id', $request->producto_id)->where('talla_id', $request->talla_id)->first();
    if (!$stock || $request->cantidad > $stock->cantidad) {
        return response()->json(['message' => 'Stock insuficiente para la cantidad solicitada'], 400);
    }

    $carrito = Carrito::where('user_id', $request->user_id)
                      ->where('producto_id', $request->producto_id)
                      ->where('talla_id', $request->talla_id)
                      ->first();

    if ($carrito) {
        $carrito->cantidadProducto += $request->cantidad;
    } else {
        $carrito = new Carrito();
        $carrito->user_id = $request->user_id;
        $carrito->producto_id = $request->producto_id;
        $carrito->talla_id = $request->talla_id;
        $carrito->cantidadProducto = $request->cantidad;
    }

    $carrito->precio = $precio;
    $carrito->save();

    return response()->json(['message' => 'Producto agregado al carrito correctamente'], 200);
}

    public function mostrar($userId)
    {
        $productosEnCarrito = Carrito::where('user_id', $userId)
        ->with(['producto.oferta', 'talla'])
        ->get()
        ->map(function ($item) {

            if ($item->producto->oferta && now()->between(new \DateTime($item->producto->oferta->inicio), new \DateTime($item->producto->oferta->fin))) {
                $descuento = $item->producto->oferta->descuento;
                $item->precio = round($item->producto->precio * (1 - $descuento / 100), 2);
            } else {
                $item->precio = $item->producto->precio;
            }
            return $item;
        });

        $numeroDeElementos = $productosEnCarrito->count();

        return response()->json($productosEnCarrito);
    }

    public function eliminarProducto($userId, $productoId)
{
    try {
        $carrito = Carrito::where('user_id', $userId)
                          ->where('producto_id', $productoId)
                          ->first();

            $carrito->delete();

        return response()->json(['message' => 'Producto eliminado del carrito correctamente'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al eliminar el producto del carrito', 'error' => $e->getMessage()], 500);
    }
}

public function incrementarCantidad($userId, $productoId)
{
    $carrito = Carrito::where('user_id', $userId)
                      ->where('producto_id', $productoId)
                      ->first();

    if (!$carrito) {
        return response()->json(['message' => 'Producto no encontrado'], 404);
    }

    $stock = Stock::where('producto_id', $productoId)->where('talla_id', $carrito->talla_id)->first();

    if (!$stock) {
        return response()->json(['message' => 'Stock no disponible'], 404);
    }

    if ($carrito->cantidadProducto + 1 <= $stock->cantidad) {
        $carrito->cantidadProducto += 1;
        $carrito->save();
        return response()->json(['message' => 'Cantidad incrementada'], 200);
    } else {
        return response()->json(['message' => 'No hay suficiente stock disponible'], 400);
    }
}

    public function disminuirCantidad($userId, $productoId)
    {
        try {
            $carrito = Carrito::where('user_id', $userId)
                            ->where('producto_id', $productoId)
                            ->first();

            if (!$carrito) {
                return response()->json(['message' => 'Producto no encontrado en el carrito'], 404);
            }

            if ($carrito->cantidadProducto > 1) {
                $carrito->cantidadProducto--;
                $carrito->save();
                return response()->json(['message' => 'Cantidad decrementada'], 200);
            } else {
                return response()->json(['message' => 'La cantidad no puede ser menor que uno'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al disminuir la cantidad del producto', 'error' => $e->getMessage()], 500);
        }
    }

    public function cambiarTalla(Request $request, $userId)
    {
        $request->validate([
            'producto_id' => 'required|integer|exists:productos,id',
            'nueva_talla_id' => 'required|integer|exists:tallas,id'
        ]);

        try {
            $carritoItem = Carrito::where('user_id', $userId)
                                ->where('producto_id', $request->producto_id)
                                ->firstOrFail();

            $carritoItem->talla_id = $request->nueva_talla_id;
            $carritoItem->cantidadProducto = 1;
            $carritoItem->save();

            return response()->json(['message' => 'Talla y cantidad actualizadas correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar la talla del producto en el carrito', 'error' => $e->getMessage()], 404);
        }
    }   
    
}