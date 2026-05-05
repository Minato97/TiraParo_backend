<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MascotaUsuario;
use Illuminate\Http\Request;

class MascotaController extends Controller
{
    // GET /api/mascota
    public function show(Request $request)
    {
        $mascota = $request->user()->mascota;

        if (! $mascota) {
            $mascota = MascotaUsuario::create([
                'user_id'        => $request->user()->id,
                'nombre'         => 'Plantita',
                'tipo'           => 'planta',
                'nivel_salud'    => 80,
                'mensaje_actual' => '¡Hola! Escanea tu primer residuo para empezar 🌱',
            ]);
        }

        // Degradar salud por inactividad antes de responder
        $mascota->degradarSalud();

        return response()->json([
            'mascota' => $mascota,
            'estado'  => $mascota->estado,
            'mensaje' => $this->generarMensaje($mascota),
        ]);
    }

    // PUT /api/mascota — Actualizar nombre y tipo
    public function update(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:50',
            'tipo'   => 'sometimes|in:planta,criatura',
        ]);

        $mascota = $request->user()->mascota;
        $mascota->update($data);

        return response()->json($mascota->fresh());
    }

    private function generarMensaje(MascotaUsuario $mascota): string
    {
        // Mensajes base (en iOS se personalizan con Foundation Models)
        if ($mascota->nivel_salud >= 80) {
            return "¡Estoy feliz! Sigues reciclando muy bien 🌿";
        }

        if ($mascota->nivel_salud >= 50) {
            return "Voy bien, ¡pero no me abandones! Escanea algo hoy 🌱";
        }

        if ($mascota->nivel_salud >= 20) {
            return "Oye, llevas días sin escanear nada... mi hoja está café 🍂";
        }

        return "¡Auxilio! Estoy a punto de marcharme 😢 ¡Escanea algo ya!";
    }
}
