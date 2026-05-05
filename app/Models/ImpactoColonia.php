<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImpactoColonia extends Model
{
    protected $table = 'impacto_colonias';

    protected $fillable = [
        'colonia',
        'alcaldia',
        'ciudad',
        'mes',
        'anio',
        'kg_reciclados',
        'co2_evitado_kg',
        'num_escaneos',
        'num_usuarios',
    ];

    protected function casts(): array
    {
        return [
            'kg_reciclados'  => 'float',
            'co2_evitado_kg' => 'float',
        ];
    }

    // Actualiza o crea el registro de impacto para la colonia/mes
    public static function registrarEscaneo(
        string $colonia,
        string $alcaldia,
        float  $pesoKg,
        float  $co2EvitadoKg,
        int    $userId
    ): void {
        $mes  = now()->month;
        $anio = now()->year;

        $registro = self::firstOrCreate(
            compact('colonia', 'alcaldia', 'mes', 'anio'),
            ['ciudad' => 'Ciudad de México', 'num_usuarios' => 0]
        );

        $registro->increment('kg_reciclados', $pesoKg);
        $registro->increment('co2_evitado_kg', $co2EvitadoKg);
        $registro->increment('num_escaneos');

        // Contar usuarios únicos en este mes/colonia
        $registro->num_usuarios = Escaneo::where('colonia_escaneo', $colonia)
            ->where('alcaldia_escaneo', $alcaldia)
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->distinct('user_id')
            ->count('user_id');
        $registro->save();
    }
}
