<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaResiduo extends Model
{
    protected $table = 'categorias_residuos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_destino',
        'ml_label',
        'icono_sf',
        'color_hex',
        'puntos_base',
        'peso_promedio_kg',
        'co2_por_kg',
        'agua_por_kg',
        'instrucciones',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo'           => 'boolean',
            'peso_promedio_kg' => 'float',
            'co2_por_kg'       => 'float',
            'agua_por_kg'      => 'float',
        ];
    }

    public function escaneos()
    {
        return $this->hasMany(Escaneo::class, 'categoria_id');
    }

    public function badges()
    {
        return $this->hasMany(Badge::class, 'categoria_ml_label', 'ml_label');
    }
}
