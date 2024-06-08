<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        return response()->json($admins);
    }

    public function añadir(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:6', 
                'regex:/[A-Z]/', 
                'regex:/[0-9]/'
            ],
            'fecha_nacimiento' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->toDateString()
            ],
            'telefono' => [
                'required',
                'digits:9',
                'regex:/^[67][0-9]{8}$/'
            ],
            'genero' => 'required|in:hombre,mujer',

        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
            'telefono' => $validatedData['telefono'],
            'genero' => $validatedData['genero']
        ]);

        $adminRole = Role::findByName('admin'); 
        $user->assignRole($adminRole);

        return response()->json([
            'message' => 'Admin creado correctamente',
            'user' => $user
        ], 201);
    }

    public function editar($id)
    {
        $admin = User::findOrFail($id);
        return response()->json($admin);
    }
    
    public function eliminar($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Admin eliminado']);
    }

    public function actualizar(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'fecha_nacimiento' => 'required|date|before_or_equal:' . now()->subYears(18)->toDateString(),
            'telefono' => 'required|digits:9|regex:/^[67][0-9]{8}$/',
            'password' => 'nullable|string|min:6|regex:/[A-Z]/|regex:/[0-9]/',
            'genero' => 'nullable|in:hombre,mujer',
        ]);

        $userData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
            'telefono' => $validatedData['telefono'],
        ];

        if ($request->has('password')) {
            $userData['password'] = Hash::make($validatedData['password']);
        }

        if ($request->has('genero')) {
            $userData['genero'] = $validatedData['genero'];
        }

        $user->update($userData);

        return response()->json(['message' => 'Admin actualizado correctamente', 'user' => $user]);
    }

}
