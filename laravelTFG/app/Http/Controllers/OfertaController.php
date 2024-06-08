<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\Oferta;
use App\Models\Producto;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    public function crearOferta(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'descuento' => 'required|numeric|min:0|max:100',
            'inicio' => 'required|date',
            'fin' => 'required|date',
        ]);

        $oferta = new Oferta();
        $oferta->nombre = $request->nombre;
        $oferta->descuento = $request->descuento;
        $oferta->inicio = $request->inicio;
        $oferta->fin = $request->fin;
        $oferta->save();

        return response()->json(['message' => 'Oferta creada exitosamente', 'oferta' => $oferta]);
    }

    public function asignarOferta(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'oferta_id' => 'nullable|exists:ofertas,id'
        ]);

        $producto = Producto::find($request->producto_id);

        if ($request->oferta_id) {
            $oferta = Oferta::find($request->oferta_id);
            if ($oferta && $oferta->fin <= $oferta->inicio) {
                return response()->json(['message' => 'La fecha de fin de la oferta no puede ser menor o igual que la fecha de inicio'], 400);
            }
        }

        $producto->oferta_id = $request->oferta_id;
        $producto->save();

        return response()->json(['message' => 'Oferta asignada al producto exitosamente']);
    }

    public function listarOfertas()
    {
        $ofertas = Oferta::all();
        return response()->json($ofertas);
    }

    public function obtenerOferta($id)
    {
        $oferta = Oferta::findOrFail($id);
        return response()->json($oferta);
    }

    public function actualizarOferta(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string',
            'descuento' => 'required|numeric|min:0|max:100',
            'inicio' => 'required|date',
            'fin' => 'required|date',
        ]);

        $oferta = Oferta::findOrFail($id);
        $oferta->nombre = $request->nombre;
        $oferta->descuento = $request->descuento;
        $oferta->inicio = $request->inicio;
        $oferta->fin = $request->fin;
        $oferta->save();

        return response()->json(['message' => 'Oferta actualizada exitosamente', 'oferta' => $oferta]);
    }  

    public function asignarOfertaCat(Request $request)
{
    $request->validate([
        'categoria_id' => 'required|exists:categorias,id',
        'oferta_id' => 'nullable|exists:ofertas,id'
    ]);

    $productos = Producto::where('categoria_id', $request->categoria_id)->get();

    if ($request->oferta_id) {
        $oferta = Oferta::find($request->oferta_id);
        if ($oferta && $oferta->fin <= $oferta->inicio) {
            return response()->json(['message' => 'La fecha de fin de la oferta no puede ser menor o igual que la fecha de inicio'], 400);
        }
    }

    foreach ($productos as $producto) {
        $producto->oferta_id = $request->oferta_id;
        $producto->save();
    }

    return response()->json(['message' => 'Oferta asignada a todos los productos de la categoría exitosamente']);
}

public function quitarOfertaCat(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id'
        ]);

        $productos = Producto::where('categoria_id', $request->categoria_id)->get();

        foreach ($productos as $producto) {
            $producto->oferta_id = null;
            $producto->save();
        }

        return response()->json(['message' => 'Oferta quitada de todos los productos de la categoría exitosamente']);
    }

}
