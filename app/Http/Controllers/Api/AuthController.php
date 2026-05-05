<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MascotaUsuario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'nombres'          => 'required|string|max:255',
            'apellido_paterno' => 'nullable|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:6|confirmed',
            'colonia'          => 'nullable|string|max:255',
            'alcaldia'         => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'nombres'          => $data['nombres'],
            'apellido_paterno' => $data['apellido_paterno'] ?? null,
            'apellido_materno' => $data['apellido_materno'] ?? null,
            'email'            => $data['email'],
            'password'         => $data['password'],
            'colonia'          => $data['colonia'] ?? null,
            'alcaldia'         => $data['alcaldia'] ?? null,
            'roles_id'         => 2,
            'estatus_id'       => 1,
        ]);

        // Crear mascota automáticamente al registrarse
        MascotaUsuario::create([
            'user_id'      => $user->id,
            'nombre'       => 'Plantita',
            'tipo'         => 'planta',
            'nivel_salud'  => 80,
            'mensaje_actual' => '¡Hola! Soy tu nueva plantita 🌱 Escanea tu primer residuo para empezar.',
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $user->load('rol', 'estatus', 'mascota'),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales inválidas.'],
            ]);
        }

        if ($user->estatus_id !== 1) {
            return response()->json(['message' => 'Cuenta inactiva.'], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $user->load('rol', 'estatus', 'mascota', 'badges'),
            'token' => $token,
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user()->load('rol', 'estatus', 'mascota', 'badges');
        $user->append('nombre_nivel');

        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'nombres'          => 'sometimes|string|max:255',
            'apellido_paterno' => 'sometimes|string|max:255',
            'apellido_materno' => 'sometimes|string|max:255',
            'colonia'          => 'sometimes|string|max:255',
            'alcaldia'         => 'sometimes|string|max:255',
            'password'         => 'sometimes|string|min:6|confirmed',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json($user->fresh()->load('rol', 'estatus', 'mascota'));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Sesión cerrada en todos los dispositivos.']);
    }
}
