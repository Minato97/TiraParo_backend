<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $table = 'badges';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono_sf',
        'color_hex',
        'criterio_tipo',
        'criterio_valor',
        'categoria_ml_label',
        'rareza',
        'temporada',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('obtenido_at');
    }
}
