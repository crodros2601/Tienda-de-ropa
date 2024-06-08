<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function index()
    {
        $tareas = Tarea::with('user')->get();
        return response()->json($tareas);
    }

    public function añadir(Request $request)
    {
        $validatedData = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pendiente,completado'
        ]);

        $tarea = Tarea::create($validatedData);
        return response()->json($tarea, 201);
    }

    public function actualizar(Request $request, Tarea $tarea)
    {
        $validatedData = $request->validate([
            'assigned_to' => 'sometimes|exists:users,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:pendiente,completado'
        ]);

        $tarea->update($validatedData);
        return response()->json($tarea);
    }

    public function mostrar(Tarea $tarea)
    {
        return response()->json($tarea);
    }


    public function eliminar(Tarea $tarea)
    {
        $tarea->delete();
        return response()->json(null, 204);
    }

    public function admin()
    {
        $userId = auth()->user()->id; 
        
        $tareas = Tarea::with('user')
                    ->where('assigned_to', $userId) 
                    ->where('status', '!=', 'completado') 
                    ->get();
        
        return response()->json($tareas);
    }
}
