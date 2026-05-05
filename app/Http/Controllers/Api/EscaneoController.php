<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\CategoriaResiduo;
use App\Models\CentroDestino;
use App\Models\Escaneo;
use App\Models\ImpactoColonia;
use App\Models\RetoComunidad;
use App\Models\UserBadge;
use App\Models\VotoEvidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EscaneoController extends Controller
{
    // POST /api/escaneos — Registrar escaneo (30% de puntos)
    public function store(Request $request)
    {
        $data = $request->validate([
            'ml_label'         => 'required|string',
            'confianza'        => 'required|numeric|min:0|max:1',
            'foto_escaneo'     => 'nullable|string', // base64 o URL desde el device
            'latitud'          => 'nullable|numeric',
            'longitud'         => 'nullable|numeric',
            'colonia'          => 'nullable|string|max:255',
            'alcaldia'         => 'nullable|string|max:255',
        ]);

        $categoria = CategoriaResiduo::where('ml_label', $data['ml_label'])
            ->where('activo', true)
            ->first();

        if (! $categoria) {
            return response()->json(['message' => 'Categoría de residuo no reconocida.'], 422);
        }

        $user         = $request->user();
        $puntosEscaneo = (int) round($categoria->puntos_base * 0.30);

        // Guardar foto si se envía en base64
        $fotoUrl = null;
        if (! empty($data['foto_escaneo'])) {
            $fotoUrl = $this->guardarFotoBase64($data['foto_escaneo'], 'escaneos');
        }

        $escaneo = Escaneo::create([
            'user_id'          => $user->id,
            'categoria_id'     => $categoria->id,
            'ml_label'         => $data['ml_label'],
            'confianza'        => $data['confianza'],
            'estado'           => 'pendiente',
            'foto_escaneo_url' => $fotoUrl,
            'puntos_escaneo'   => $puntosEscaneo,
            'latitud_escaneo'  => $data['latitud'] ?? null,
            'longitud_escaneo' => $data['longitud'] ?? null,
            'colonia_escaneo'  => $data['colonia'] ?? $user->colonia,
            'alcaldia_escaneo' => $data['alcaldia'] ?? $user->alcaldia,
            'peso_estimado_kg' => $categoria->peso_promedio_kg,
            'co2_evitado_g'    => $categoria->peso_promedio_kg * $categoria->co2_por_kg * 1000,
        ]);

        // Sumar puntos inmediatos (30%)
        $user->increment('puntos_totales', $puntosEscaneo);
        $user->increment('puntos_semana', $puntosEscaneo);
        $user->recalcularNivel();

        // Actualizar mascota
        if ($user->mascota) {
            $user->mascota->registrarEscaneo();
        }

        // Verificar badges desbloqueables
        $badgesNuevos = $this->verificarBadges($user);

        return response()->json([
            'escaneo'       => $escaneo->load('categoria'),
            'puntos_ganados' => $puntosEscaneo,
            'mensaje'       => "¡+{$puntosEscaneo} puntos! Sube la foto de evidencia para ganar {$this->puntosEvidencia($categoria)} más.",
            'badges_nuevos' => $badgesNuevos,
        ], 201);
    }

    // POST /api/escaneos/{id}/evidencia — Enviar evidencia (70% de puntos)
    public function subirEvidencia(Request $request, Escaneo $escaneo)
    {
        if ($escaneo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        if ($escaneo->estado === 'validado') {
            return response()->json(['message' => 'Este escaneo ya fue validado.'], 422);
        }

        $data = $request->validate([
            'foto_evidencia'    => 'nullable|string',     // base64
            'latitud'           => 'nullable|numeric',
            'longitud'          => 'nullable|numeric',
            'centro_destino_id' => 'nullable|integer|exists:centros_destino,id',
            'metodo_validacion' => 'required|in:geolocalizacion,foto,comunidad',
        ]);

        $user             = $request->user();
        $categoria        = $escaneo->categoria;
        $puntosEvidencia  = (int) round($categoria->puntos_base * 0.70);

        // Guardar foto de evidencia
        $fotoUrl = null;
        if (! empty($data['foto_evidencia'])) {
            $fotoUrl = $this->guardarFotoBase64($data['foto_evidencia'], 'evidencias');
        }

        $metodo   = $data['metodo_validacion'];
        $validado = true; // La validación por comunidad se difiere a votos

        $escaneo->update([
            'foto_evidencia_url'  => $fotoUrl,
            'latitud_evidencia'   => $data['latitud'] ?? null,
            'longitud_evidencia'  => $data['longitud'] ?? null,
            'centro_destino_id'   => $data['centro_destino_id'] ?? null,
            'metodo_validacion'   => $metodo,
            'puntos_evidencia'    => $metodo === 'comunidad' ? 0 : $puntosEvidencia,
            'estado'              => $metodo === 'comunidad' ? 'pendiente' : 'validado',
        ]);

        $puntosOtorgados = 0;
        if ($metodo !== 'comunidad') {
            $puntosOtorgados = $puntosEvidencia;
            $user->increment('puntos_totales', $puntosEvidencia);
            $user->increment('puntos_semana', $puntosEvidencia);
            $user->recalcularNivel();

            // Actualizar impacto ambiental del usuario
            $co2Kg = $escaneo->peso_estimado_kg * $categoria->co2_por_kg;
            $user->increment('co2_evitado_kg', $co2Kg);
            $user->increment('agua_ahorrada_litros', $escaneo->peso_estimado_kg * $categoria->agua_por_kg);
            $user->increment('arboles_equivalentes', $co2Kg / 21.7); // 1 árbol absorbe ~21.7 kg CO2/año

            // Actualizar impacto por colonia
            if ($escaneo->colonia_escaneo && $escaneo->alcaldia_escaneo) {
                ImpactoColonia::registrarEscaneo(
                    $escaneo->colonia_escaneo,
                    $escaneo->alcaldia_escaneo,
                    $escaneo->peso_estimado_kg,
                    $co2Kg,
                    $user->id
                );
            }

            // Sumar kg a retos comunitarios activos
            $this->actualizarRetos($escaneo->ml_label, $escaneo->peso_estimado_kg, $escaneo->alcaldia_escaneo);
        }

        $badgesNuevos = $this->verificarBadges($user);

        return response()->json([
            'escaneo'        => $escaneo->fresh()->load('categoria', 'centroDestino'),
            'puntos_ganados' => $puntosOtorgados,
            'mensaje'        => $metodo === 'comunidad'
                ? 'Evidencia recibida. La comunidad la verificará pronto.'
                : "¡+{$puntosOtorgados} puntos! Escaneo validado correctamente.",
            'badges_nuevos'  => $badgesNuevos,
        ]);
    }

    // GET /api/escaneos — Historial del usuario autenticado
    public function index(Request $request)
    {
        $escaneos = Escaneo::where('user_id', $request->user()->id)
            ->with('categoria', 'centroDestino')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($escaneos);
    }

    // GET /api/escaneos/{id} — Detalle de un escaneo
    public function show(Request $request, Escaneo $escaneo)
    {
        if ($escaneo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json($escaneo->load('categoria', 'centroDestino', 'votos'));
    }

    // GET /api/escaneos/pendientes-comunidad — Escaneos esperando validación por votos
    public function pendientesComunidad(Request $request)
    {
        $userId   = $request->user()->id;

        $escaneos = Escaneo::where('estado', 'pendiente')
            ->where('metodo_validacion', 'comunidad')
            ->where('user_id', '!=', $userId) // No puedes votar tus propios escaneos
            ->whereDoesntHave('votos', fn($q) => $q->where('voter_user_id', $userId))
            ->with('categoria', 'user:id,nombres')
            ->limit(10)
            ->get();

        return response()->json($escaneos);
    }

    // POST /api/escaneos/{id}/votar — Votar evidencia de otro usuario
    public function votar(Request $request, Escaneo $escaneo)
    {
        $data = $request->validate([
            'voto' => 'required|in:valido,invalido',
        ]);

        $userId = $request->user()->id;

        if ($escaneo->user_id === $userId) {
            return response()->json(['message' => 'No puedes votar tu propio escaneo.'], 422);
        }

        VotoEvidencia::firstOrCreate(
            ['escaneo_id' => $escaneo->id, 'voter_user_id' => $userId],
            ['voto' => $data['voto']]
        );

        // Si 3+ votos válidos → validar
        $votosValidos   = $escaneo->votos()->where('voto', 'valido')->count();
        $votosInvalidos = $escaneo->votos()->where('voto', 'invalido')->count();

        if ($votosValidos >= 3 && $escaneo->estado === 'pendiente') {
            $categoria       = $escaneo->categoria;
            $puntosEvidencia = (int) round($categoria->puntos_base * 0.70);
            $escaneo->update([
                'estado'           => 'validado',
                'puntos_evidencia' => $puntosEvidencia,
                'metodo_validacion' => 'comunidad',
            ]);

            $dueño = $escaneo->user;
            $dueño->increment('puntos_totales', $puntosEvidencia);
            $dueño->increment('puntos_semana', $puntosEvidencia);
            $dueño->recalcularNivel();
            $this->verificarBadges($dueño);
        } elseif ($votosInvalidos >= 3 && $escaneo->estado === 'pendiente') {
            $escaneo->update(['estado' => 'rechazado']);
        }

        return response()->json(['message' => 'Voto registrado.']);
    }

    // --- Helpers privados ---

    private function puntosEvidencia(CategoriaResiduo $categoria): int
    {
        return (int) round($categoria->puntos_base * 0.70);
    }

    private function guardarFotoBase64(string $base64, string $carpeta): ?string
    {
        try {
            // Remover header data:image/...;base64,
            $imagen = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
            $imagen = base64_decode($imagen);
            $nombre = $carpeta . '/' . uniqid() . '.jpg';
            Storage::disk('public')->put($nombre, $imagen);
            return $nombre;
        } catch (\Throwable) {
            return null;
        }
    }

    private function verificarBadges(mixed $user): array
    {
        $user->refresh();
        $badgesNuevos  = [];
        $totalEscaneos = Escaneo::where('user_id', $user->id)->count();
        $badgesActivos = Badge::where('activo', true)->get();

        foreach ($badgesActivos as $badge) {
            // Ya tiene el badge
            if ($user->badges()->where('badge_id', $badge->id)->exists()) {
                continue;
            }

            $desbloqueado = match ($badge->criterio_tipo) {
                'primer_escaneo'    => $totalEscaneos >= 1,
                'escaneos_total'    => $totalEscaneos >= $badge->criterio_valor,
                'categoria_count'   => Escaneo::where('user_id', $user->id)
                    ->where('ml_label', $badge->categoria_ml_label)
                    ->count() >= $badge->criterio_valor,
                'racha_dias'        => $user->racha_dias >= $badge->criterio_valor,
                'co2_total_kg'      => $user->co2_evitado_kg >= $badge->criterio_valor,
                'puntos_total'      => $user->puntos_totales >= $badge->criterio_valor,
                'validaciones_total' => Escaneo::where('user_id', $user->id)
                    ->where('estado', 'validado')->count() >= $badge->criterio_valor,
                default             => false,
            };

            if ($desbloqueado) {
                UserBadge::create([
                    'user_id'     => $user->id,
                    'badge_id'    => $badge->id,
                    'obtenido_at' => now(),
                ]);
                $badgesNuevos[] = $badge;
            }
        }

        return $badgesNuevos;
    }

    private function actualizarRetos(string $mlLabel, float $pesoKg, ?string $alcaldia): void
    {
        RetoComunidad::where('activo', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->where(fn($q) => $q->whereNull('tipo_material')->orWhere('tipo_material', $mlLabel))
            ->where(fn($q) => $q->whereNull('alcaldia')->orWhere('alcaldia', $alcaldia))
            ->each(fn($reto) => $reto->increment('actual_kg', $pesoKg));
    }
}
