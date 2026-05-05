<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escaneo extends Model
{
    protected $table = 'escaneos';

    protected $fillable = [
        'user_id',
        'categoria_id',
        'ml_label',
        'confianza',
        'estado',
        'foto_escaneo_url',
        'foto_evidencia_url',
        'puntos_escaneo',
        'puntos_evidencia',
        'latitud_escaneo',
        'longitud_escaneo',
        'latitud_evidencia',
        'longitud_evidencia',
        'centro_destino_id',
        'metodo_validacion',
        'peso_estimado_kg',
        'co2_evitado_g',
        'colonia_escaneo',
        'alcaldia_escaneo',
    ];

    protected function casts(): array
    {
        return [
            'confianza'          => 'float',
            'peso_estimado_kg'   => 'float',
            'co2_evitado_g'      => 'float',
            'latitud_escaneo'    => 'float',
            'longitud_escaneo'   => 'float',
            'latitud_evidencia'  => 'float',
            'longitud_evidencia' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaResiduo::class, 'categoria_id');
    }

    public function centroDestino()
    {
        return $this->belongsTo(CentroDestino::class, 'centro_destino_id');
    }

    public function votos()
    {
        return $this->hasMany(VotoEvidencia::class);
    }

    public function getPuntosTotalAttribute(): int
    {
        return $this->puntos_escaneo + $this->puntos_evidencia;
    }
}
