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

    // ─────────────────────────────────────────────────────────────────────
    // PERFIL DE HÁBITOS
    // Guarda el cuestionario de hábitos del usuario (onboarding de prevención).
    // La lógica de mensajes motivacionales vive en la app iOS —
    // el backend solo persiste el JSON para recuperarlo en otro dispositivo.
    // ─────────────────────────────────────────────────────────────────────

    public function guardarHabitos(Request $request)
    {
        $data = $request->validate([
            'habitos_perfil'                    => 'required|array',
            'habitos_perfil.dieta'              => 'required|in:omnivoro,vegetariano,vegano,flexitariano',
            'habitos_perfil.frecuencia_cocina'  => 'required|in:diario,semanal,pocas,nunca',
            'habitos_perfil.bebidas_envase'     => 'required|in:mucho,regular,poco,nada',
            'habitos_perfil.habito_compras'     => 'required|in:mercado,supermercado,tiendita,delivery',
            'habitos_perfil.residuos_ropa'      => 'required|in:seguido,ocasional,nunca,reutiliza',
            'habitos_perfil.papel_carton'       => 'required|in:bastante,regular,poco,nada',
        ]);

        $request->user()->update([
            'habitos_perfil' => $data['habitos_perfil'],
        ]);

        return response()->json([
            'ok'             => true,
            'habitos_perfil' => $request->user()->fresh()->habitos_perfil,
        ]);
    }

    public function getHabitos(Request $request)
    {
        return response()->json([
            'habitos_perfil' => $request->user()->habitos_perfil,
        ]);
    }
}
