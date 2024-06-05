<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:5',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|regex:/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/',
                'cPassword' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            // Crear el usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Crear un token de acceso
            $token = $user->createToken('authToken')->plainTextToken;

            // Retornar la respuesta de éxito con el token
            return response()->json(['message' => 'User registered successfully', 'token' => $token, 'user' => $user], 201);
        } catch (\Exception $e) {
            \Log::error('Error registering user: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while registering user.', 'message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        // Validar las credenciales del usuario
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar autenticar al usuario
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Crear un token de acceso
        $token = $user->createToken('authToken')->plainTextToken;

        // Crear una cookie segura para el token
        $cookie = cookie('auth_token', $token, 60 * 24 * 7, null, null, true, true, false, 'None'); // Cookie válida por 7 días

        // Devolver la respuesta exitosa con el token en una cookie
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            // 'user' => $user,
            'auth_token' => $token,
        ], 200)->cookie($cookie);
    }


    public function logout(Request $request)
    {
        // Invalida el token de autenticación del usuario
        $request->user()->tokens()->delete();


        // Invalidar la sesión en el servidor
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Elimina las cookies de sesión y CSRF
        $response = response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ], 200);  // Código de estado 200 (OK)

        // Configura las cookies para que se eliminen
        $response->headers->clearCookie('XSRF-TOKEN');
        $response->headers->clearCookie('laravel_session');

        return $response;
    }


    public function user(Request $request)
    {
        // Devolver el usuario autenticado
        return response()->json([
            'success' => true,
            'message' => 'User loaded successfully',
            'user' => $request->user()
        ], 200);  // Código de estado 200 (OK)
    }
}
// namespace App\Http\Controllers;

// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;

// class AuthController extends Controller
// {
//     // Método para mostrar todos los usuarios
//     public function index()
//     {
//         $users = User::all();
//         return response()->json(['users' => $users]);
//     }

//     // Método para mostrar un usuario específico
//     public function show($id)
//     {
//         $user = User::findOrFail($id);
//         return response()->json(['user' => $user]);
//     }

//     // Método para crear un nuevo usuario
//     public function store(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'password' => 'required|string|min:8',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['error' => $validator->errors()], 422);
//         }

//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => bcrypt($request->password),
//         ]);

//         return response()->json(['message' => 'Usuario creado exitosamente', 'user' => $user]);
//     }

//     // Método para actualizar un usuario
//     public function update(Request $request, $id)
//     {
//         $user = User::findOrFail($id);

//         $validator = Validator::make($request->all(), [
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['error' => $validator->errors()], 422);
//         }

//         $user->update($request->all());

//         return response()->json(['message' => 'Usuario actualizado exitosamente', 'user' => $user]);
//     }

//     // Método para eliminar un usuario
//     public function destroy($id)
//     {
//         $user = User::findOrFail($id);
//         $user->delete();

//         return response()->json(['message' => 'Usuario eliminado exitosamente']);
//     }
// }
