<?php

namespace App\Http\Controllers;

use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    public function mostrar($userId)
    {
        $wishlist = WishList::where('user_id', $userId)->with('producto')->get();

        return response()->json($wishlist);
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'user_id' => 'required|exists:users,id'
        ]);
    
        $existingItem = WishList::where('user_id', $request->user_id)
                                ->where('producto_id', $request->producto_id)
                                ->first();
    
        if ($existingItem) {
            return response()->json(['message' => 'El producto ya está en la wishlist.'], 409); 
        }
    
        try {
            $wishlist = new WishList();
            $wishlist->nombre = 'Mi Wishlist'; 
            $wishlist->user_id = $request->user_id;
            $wishlist->producto_id = $request->producto_id;
            $wishlist->save();
    
            return response()->json(['message' => 'Producto agregado a la wishlist con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se pudo agregar el producto a la wishlist.', 'error' => $e->getMessage()], 500);
        }
    }
    

public function eliminar($userId, $productoId)
{
    try {
        $wishlistItem = WishList::where('user_id', $userId)
                                ->where('producto_id', $productoId)
                                ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            return response()->json(['message' => 'Producto eliminado de la wishlist con éxito.']);
        } else {
            return response()->json(['message' => 'Producto no encontrado en la wishlist.'], 404);
        }
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error interno del servidor.', 'error' => $e->getMessage()], 500);
    }
}

}
