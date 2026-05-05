<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Escaneo;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    // GET /api/perfil/impacto — Dashboard de impacto ambiental personal
    public function impacto(Request $request)
    {
        $user = $request->user();

        $totalEscaneos     = Escaneo::where('user_id', $user->id)->count();
        $escaneosValidados = Escaneo::where('user_id', $user->id)->where('estado', 'validado')->count();

        // Breakdown por categoría
        $porCategoria = Escaneo::where('user_id', $user->id)
            ->where('estado', 'validado')
            ->selectRaw('ml_label, COUNT(*) as total, SUM(peso_estimado_kg) as kg_total')
            ->groupBy('ml_label')
            ->get();

        // Actividad últimos 7 días
        $actividadSemanal = Escaneo::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as fecha, COUNT(*) as escaneos')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return response()->json([
            'usuario' => [
                'nombre_nivel'       => $user->nombre_nivel,
                'nivel'              => $user->nivel,
                'puntos_totales'     => $user->puntos_totales,
                'puntos_semana'      => $user->puntos_semana,
                'racha_dias'         => $user->racha_dias,
            ],
            'impacto' => [
                'co2_evitado_kg'       => round($user->co2_evitado_kg, 3),
                'agua_ahorrada_litros' => round($user->agua_ahorrada_litros, 1),
                'arboles_equivalentes' => round($user->arboles_equivalentes, 2),
                // Frases para la app (Foundation Models puede personalizarlas en iOS)
                'frase_co2'    => "Has evitado " . round($user->co2_evitado_kg, 1) . " kg de CO₂",
                'frase_arboles' => "Equivale a plantar " . (int) $user->arboles_equivalentes . " árbol(es)",
                'frase_agua'   => "Has ahorrado " . round($user->agua_ahorrada_litros) . " litros de agua",
            ],
            'estadisticas' => [
                'total_escaneos'     => $totalEscaneos,
                'escaneos_validados' => $escaneosValidados,
                'por_categoria'      => $porCategoria,
            ],
            'actividad_semanal' => $actividadSemanal,
        ]);
    }

    // GET /api/perfil/badges — Badges del usuario
    public function badges(Request $request)
    {
        $user = $request->user();

        $badgesObtenidos = $user->badges()->get()->map(function ($badge) {
            return [
                'id'          => $badge->id,
                'nombre'      => $badge->nombre,
                'descripcion' => $badge->descripcion,
                'icono_sf'    => $badge->icono_sf,
                'color_hex'   => $badge->color_hex,
                'rareza'      => $badge->rareza,
                'obtenido_at' => $badge->pivot->obtenido_at,
            ];
        });

        return response()->json([
            'obtenidos' => $badgesObtenidos,
            'total'     => $badgesObtenidos->count(),
        ]);
    }

    // GET /api/perfil/historial — Historial de escaneos con stats
    public function historial(Request $request)
    {
        $escaneos = Escaneo::where('user_id', $request->user()->id)
            ->with('categoria')
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($escaneos);
    }
}
