<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoriaResiduo;
use Illuminate\Http\Request;

class CategoriaResiduoController extends Controller
{
    // GET /api/categorias
    public function index()
    {
        return response()->json(
            CategoriaResiduo::where('activo', true)->orderBy('nombre')->get()
        );
    }

    // GET /api/categorias/{mlLabel} — Buscar por label del modelo ML
    public function showByLabel(string $mlLabel)
    {
        $categoria = CategoriaResiduo::where('ml_label', $mlLabel)
            ->where('activo', true)
            ->firstOrFail();

        return response()->json($categoria);
    }
}
