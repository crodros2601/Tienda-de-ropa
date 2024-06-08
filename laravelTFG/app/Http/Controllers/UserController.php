<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }

    public function mostrar($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user);
    }

    public function actualizar(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
            'telefono' => [
                'required',
                'digits:9',
                'regex:/^[67][0-9]{8}$/'
            ]
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de caracteres.',
            'name.max' => 'El nombre no puede exceder los 255 caracteres.',
            'email.required' => 'El email es obligatorio.',
            'email.string' => 'El email debe ser una cadena de caracteres.',
            'email.email' => 'El formato del email es inválido.',
            'email.max' => 'El email no puede exceder los 255 caracteres.',
            'email.unique' => 'El email ya está en uso.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener 9 dígitos.',
            'telefono.regex' => 'El teléfono debe empezar con 6 o 7.'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->telefono = $request->telefono;
        $user->save();
    
        return response()->json($user);
    }
    
}
