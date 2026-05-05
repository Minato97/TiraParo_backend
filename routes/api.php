<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaResiduoController;
use App\Http\Controllers\Api\CentroDestinoController;
use App\Http\Controllers\Api\ComunidadController;
use App\Http\Controllers\Api\EscaneoController;
use App\Http\Controllers\Api\MascotaController;
use App\Http\Controllers\Api\PerfilController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// AUTH — pública
// ─────────────────────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// ─────────────────────────────────────────────────────────────────────────────
// CATÁLOGOS — públicos (la app iOS los descarga al arrancar)
// ─────────────────────────────────────────────────────────────────────────────
Route::prefix('categorias')->group(function () {
    Route::get('/',          [CategoriaResiduoController::class, 'index']);
    Route::get('/{mlLabel}', [CategoriaResiduoController::class, 'showByLabel']);
});

Route::prefix('centros')->group(function () {
    Route::get('/',    [CentroDestinoController::class, 'index']);
    Route::get('/{centroDestino}', [CentroDestinoController::class, 'show']);
});

// ─────────────────────────────────────────────────────────────────────────────
// RUTAS PROTEGIDAS — requieren token Sanctum
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // ── Auth ──────────────────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::get('/profile',     [AuthController::class, 'profile']);
        Route::put('/profile',     [AuthController::class, 'updateProfile']);
        Route::post('/logout',     [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    });

    // ── Escaneos ──────────────────────────────────────────────────────────
    Route::prefix('escaneos')->group(function () {
        Route::get('/',                         [EscaneoController::class, 'index']);
        Route::post('/',                        [EscaneoController::class, 'store']);
        Route::get('/pendientes-comunidad',     [EscaneoController::class, 'pendientesComunidad']);
        Route::get('/{escaneo}',                [EscaneoController::class, 'show']);
        Route::post('/{escaneo}/evidencia',     [EscaneoController::class, 'subirEvidencia']);
        Route::post('/{escaneo}/votar',         [EscaneoController::class, 'votar']);
    });

    // ── Perfil e impacto ──────────────────────────────────────────────────
    Route::prefix('perfil')->group(function () {
        Route::get('/impacto',   [PerfilController::class, 'impacto']);
        Route::get('/badges',    [PerfilController::class, 'badges']);
        Route::get('/historial', [PerfilController::class, 'historial']);
    });

    // ── Mascota virtual ───────────────────────────────────────────────────
    Route::prefix('mascota')->group(function () {
        Route::get('/',  [MascotaController::class, 'show']);
        Route::put('/',  [MascotaController::class, 'update']);
    });

    // ── Comunidad ─────────────────────────────────────────────────────────
    Route::prefix('comunidad')->group(function () {
        Route::get('/colonias',    [ComunidadController::class, 'colonias']);
        Route::get('/leaderboard', [ComunidadController::class, 'leaderboard']);
        Route::get('/retos',       [ComunidadController::class, 'retos']);
        Route::get('/muro',        [ComunidadController::class, 'muro']);
        Route::get('/stats',       [ComunidadController::class, 'stats']);
    });
});
