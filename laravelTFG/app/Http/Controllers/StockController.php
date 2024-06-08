<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Stock;

class StockController extends Controller
{
    public function getStockPorProductoTalla($productoId, $tallaId)
    {
        $stock = Stock::where('producto_id', $productoId)->where('talla_id', $tallaId)->first();

        if (!$stock) {
            return response()->json(['message' => 'Stock no encontrado'], 404);
        }

        return response()->json($stock);
    }

    public function getTodoStock()
    {
        $stocks = Stock::with(['producto', 'talla'])->get();

        if ($stocks->isEmpty()) {
            return response()->json(['message' => 'No hay stock disponible para ningún producto'], 404);
        }

        return response()->json($stocks);
    }

    public function getProductoStock($productId)
    {
        $stocks = Stock::where('producto_id', $productId)->with(['talla'])->get();

        if ($stocks->isEmpty()) {
                return response()->json(['message' => 'No hay stock para este producto'], 404);
        }

        return response()->json($stocks);
    }

    public function incrementarStock(Request $request, $stockId)
    {
        $cantidad = $request->input('cantidad', 1);
        $stock = Stock::findOrFail($stockId);
        $stock->cantidad += $cantidad;
        $stock->save();

        return response()->json(['message' => 'Stock incrementado con éxito', 'stock' => $stock]);
    }

    public function disminuirStock(Request $request)
    {
        $productoId = $request->input('producto_id');
        $tallaId = $request->input('talla_id');
        $cantidad = $request->input('cantidad');
    
        $stock = Stock::where('producto_id', $productoId)->where('talla_id', $tallaId)->first();
    
        if (!$stock || $stock->cantidad < $cantidad) {
            return response()->json(['message' => 'No hay suficiente stock para realizar la operación'], 400);
        }
    
        $stock->cantidad -= $cantidad;
        $stock->save();
    
        return response()->json(['message' => 'Stock actualizado con éxito', 'stock' => $stock]);
    }

    public function decrementarStock(Request $request, $stockId)
{
    $cantidad = $request->input('cantidad', 1);
    $stock = Stock::findOrFail($stockId);
    
    if ($stock->cantidad < $cantidad) {
        return response()->json(['error' => 'La cantidad a disminuir es mayor que el stock disponible'], 400);
    }

    $stock->cantidad -= $cantidad;
    $stock->save();

    return response()->json(['message' => 'Stock decrementado con éxito', 'stock' => $stock]);
}

}