<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
     public function register()
{
    $validator = validator(request()->all(), [
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

    $messages = [
        'email.unique' => 'El email ya existe.',
        'password.regex' => 'La contraseña necesita tener al menos 6 caracteres, 1 mayúscula y 1 número.',
        'fecha_nacimiento.before_or_equal' => 'Debes tener al menos 18 años.',
        'telefono.digits' => 'El teléfono debe tener 9 dígitos.',
        'telefono.regex' => 'Debe de empezar por 6 y 7.',
        'genero.required' => 'El género es obligatorio.',
        'genero.in' => 'El género debe ser hombre o mujer.'
    ];
    
    $validator->setCustomMessages($messages);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $credentials = $validator->validated();
    $credentials['password'] = bcrypt($credentials['password']);

    $user = User::create($credentials);
    return response()->json(['user' => $user], 201);
}
    
     public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse    
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
{
    $user = auth()->user();
    $roles = $user->getRoleNames();

    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60,
        'user' => $user,
        'roles' => $roles
    ]);
}

    public function userCount()
    {
        $count = User::count();
        return response()->json(['count' => $count]);
    }
    
}