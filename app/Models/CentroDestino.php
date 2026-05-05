<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentroDestino extends Model
{
    protected $table = 'centros_destino';

    protected $fillable = [
        'nombre',
        'tipo',
        'descripcion',
        'direccion',
        'latitud',
        'longitud',
        'alcaldia',
        'colonia',
        'ciudad',
        'horario',
        'telefono',
        'sitio_web',
        'materiales_aceptados',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'materiales_aceptados' => 'array',
            'activo'               => 'boolean',
            'latitud'              => 'float',
            'longitud'             => 'float',
        ];
    }

    public function escaneos()
    {
        return $this->hasMany(Escaneo::class);
    }

    // Distancia en km desde coordenadas dadas (Haversine)
    public function distanciaDesde(float $lat, float $lng): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($this->latitud - $lat);
        $dLng = deg2rad($this->longitud - $lng);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat)) * cos(deg2rad($this->latitud)) * sin($dLng / 2) ** 2;
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
