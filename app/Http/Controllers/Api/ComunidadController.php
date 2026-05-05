<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Escaneo;
use App\Models\ImpactoColonia;
use App\Models\RetoComunidad;
use App\Models\User;
use Illuminate\Http\Request;

class ComunidadController extends Controller
{
    // GET /api/comunidad/colonias — Mapa de impacto por colonia (mes actual)
    public function colonias(Request $request)
    {
        $mes  = $request->integer('mes', now()->month);
        $anio = $request->integer('anio', now()->year);

        $colonias = ImpactoColonia::where('mes', $mes)
            ->where('anio', $anio)
            ->orderByDesc('kg_reciclados')
            ->get()
            ->map(fn($c) => [
                'colonia'        => $c->colonia,
                'alcaldia'       => $c->alcaldia,
                'kg_reciclados'  => round($c->kg_reciclados, 2),
                'co2_evitado_kg' => round($c->co2_evitado_kg, 2),
                'num_escaneos'   => $c->num_escaneos,
                'num_usuarios'   => $c->num_usuarios,
            ]);

        return response()->json([
            'mes'    => $mes,
            'anio'   => $anio,
            'datos'  => $colonias,
        ]);
    }

    // GET /api/comunidad/leaderboard — Ranking de usuarios
    public function leaderboard(Request $request)
    {
        $tipo = $request->input('tipo', 'global'); // global | alcaldia | semana

        $query = User::where('estatus_id', 1)
            ->select('id', 'nombres', 'apellido_paterno', 'foto', 'colonia', 'alcaldia',
                'puntos_totales', 'puntos_semana', 'nivel', 'co2_evitado_kg');

        if ($tipo === 'alcaldia' && $request->filled('alcaldia')) {
            $query->where('alcaldia', $request->alcaldia);
        }

        $campo = $tipo === 'semana' ? 'puntos_semana' : 'puntos_totales';

        $usuarios = $query->orderByDesc($campo)->limit(50)->get()
            ->map(fn($u, $i) => [
                'posicion'       => $i + 1,
                'id'             => $u->id,
                'nombre'         => $u->nombres . ' ' . $u->apellido_paterno,
                'foto'           => $u->foto,
                'colonia'        => $u->colonia,
                'alcaldia'       => $u->alcaldia,
                'puntos'         => $tipo === 'semana' ? $u->puntos_semana : $u->puntos_totales,
                'nivel'          => $u->nivel,
                'co2_evitado_kg' => round($u->co2_evitado_kg, 2),
            ]);

        return response()->json([
            'tipo'     => $tipo,
            'usuarios' => $usuarios,
        ]);
    }

    // GET /api/comunidad/retos — Retos comunitarios activos
    public function retos()
    {
        $retos = RetoComunidad::where('activo', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($r) => [
                'id'           => $r->id,
                'titulo'       => $r->titulo,
                'descripcion'  => $r->descripcion,
                'meta_kg'      => $r->meta_kg,
                'actual_kg'    => round($r->actual_kg, 2),
                'porcentaje'   => $r->porcentaje,
                'completado'   => $r->completado,
                'fecha_fin'    => $r->fecha_fin->format('Y-m-d'),
                'dias_restantes' => max(0, now()->diffInDays($r->fecha_fin)),
                'tipo_material' => $r->tipo_material,
                'alcaldia'     => $r->alcaldia,
            ]);

        return response()->json($retos);
    }

    // GET /api/comunidad/muro — Últimos escaneos validados (feed comunitario)
    public function muro(Request $request)
    {
        $escaneos = Escaneo::where('estado', 'validado')
            ->whereNotNull('foto_evidencia_url')
            ->with('categoria', 'user:id,nombres,apellido_paterno,foto,colonia')
            ->orderByDesc('updated_at')
            ->paginate(20);

        return response()->json($escaneos);
    }

    // GET /api/comunidad/stats — Stats globales de la app
    public function stats()
    {
        $totalEscaneos   = Escaneo::where('estado', 'validado')->count();
        $totalUsuarios   = User::where('estatus_id', 1)->count();
        $totalCo2Kg      = User::sum('co2_evitado_kg');
        $totalKgMes      = ImpactoColonia::where('mes', now()->month)
            ->where('anio', now()->year)
            ->sum('kg_reciclados');

        return response()->json([
            'total_escaneos_validados' => $totalEscaneos,
            'total_usuarios'           => $totalUsuarios,
            'co2_evitado_kg_total'     => round($totalCo2Kg, 2),
            'kg_reciclados_mes'        => round($totalKgMes, 2),
            'arboles_equivalentes'     => round($totalCo2Kg / 21.7, 1),
        ]);
    }
}
