<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MascotaUsuario extends Model
{
    protected $table = 'mascotas_usuario';

    protected $fillable = [
        'user_id',
        'nombre',
        'tipo',
        'nivel_salud',
        'escaneos_semana',
        'ultimo_escaneo_at',
        'racha_semanas',
        'mensaje_actual',
    ];

    protected function casts(): array
    {
        return [
            'ultimo_escaneo_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Registra un escaneo y sube la salud
    public function registrarEscaneo(): void
    {
        $this->nivel_salud      = min(100, $this->nivel_salud + 20);
        $this->escaneos_semana  += 1;
        $this->ultimo_escaneo_at = now();
        $this->save();
    }

    // Llamar periódicamente para degradar salud por inactividad
    public function degradarSalud(): void
    {
        if ($this->ultimo_escaneo_at && $this->ultimo_escaneo_at->diffInDays(now()) >= 1) {
            $this->nivel_salud = max(0, $this->nivel_salud - 10);
            $this->save();
        }
    }

    public function getEstadoAttribute(): string
    {
        return match (true) {
            $this->nivel_salud >= 80 => 'excelente',
            $this->nivel_salud >= 50 => 'bien',
            $this->nivel_salud >= 20 => 'necesita_ayuda',
            default                  => 'critico',
        };
    }
}
