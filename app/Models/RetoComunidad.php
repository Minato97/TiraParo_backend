<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetoComunidad extends Model
{
    protected $table = 'retos_comunitarios';

    protected $fillable = [
        'titulo',
        'descripcion',
        'meta_kg',
        'actual_kg',
        'fecha_inicio',
        'fecha_fin',
        'tipo_material',
        'alcaldia',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'meta_kg'     => 'float',
            'actual_kg'   => 'float',
            'fecha_inicio' => 'date',
            'fecha_fin'    => 'date',
            'activo'       => 'boolean',
        ];
    }

    public function getPorcentajeAttribute(): float
    {
        if ($this->meta_kg <= 0) {
            return 0;
        }
        return min(100, round(($this->actual_kg / $this->meta_kg) * 100, 1));
    }

    public function getCompletadoAttribute(): bool
    {
        return $this->actual_kg >= $this->meta_kg;
    }
}
