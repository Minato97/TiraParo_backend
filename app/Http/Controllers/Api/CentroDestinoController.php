<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CentroDestino;
use Illuminate\Http\Request;

class CentroDestinoController extends Controller
{
    // GET /api/centros
    // Parámetros opcionales: tipo, latitud, longitud, radio_km, alcaldia, material
    public function index(Request $request)
    {
        $query = CentroDestino::where('activo', true);

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('alcaldia')) {
            $query->where('alcaldia', 'like', '%' . $request->alcaldia . '%');
        }

        if ($request->filled('material')) {
            $query->whereJsonContains('materiales_aceptados', $request->material);
        }

        $centros = $query->get();

        // Si se envían coordenadas, ordenar por distancia y filtrar por radio
        if ($request->filled('latitud') && $request->filled('longitud')) {
            $lat    = (float) $request->latitud;
            $lng    = (float) $request->longitud;
            $radio  = (float) ($request->radio_km ?? 10);

            $centros = $centros
                ->map(function ($centro) use ($lat, $lng) {
                    $centro->distancia_km = round($centro->distanciaDesde($lat, $lng), 2);
                    return $centro;
                })
                ->filter(fn($c) => $c->distancia_km <= $radio)
                ->sortBy('distancia_km')
                ->values();
        }

        return response()->json($centros);
    }

    // GET /api/centros/{id}
    public function show(CentroDestino $centroDestino)
    {
        return response()->json($centroDestino);
    }
}
