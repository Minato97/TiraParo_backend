<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'password',
        'foto',
        'colonia',
        'alcaldia',
        'roles_id',
        'estatus_id',
        'puntos_totales',
        'puntos_semana',
        'nivel',
        'racha_dias',
        'co2_evitado_kg',
        'agua_ahorrada_litros',
        'arboles_equivalentes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'co2_evitado_kg'    => 'float',
            'agua_ahorrada_litros' => 'float',
            'arboles_equivalentes' => 'float',
        ];
    }

    // --- Relaciones ---

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'roles_id');
    }

    public function estatus()
    {
        return $this->belongsTo(Estatus::class, 'estatus_id');
    }

    public function escaneos()
    {
        return $this->hasMany(Escaneo::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('obtenido_at')
            ->orderByPivot('obtenido_at', 'desc');
    }

    public function mascota()
    {
        return $this->hasOne(MascotaUsuario::class);
    }

    public function votosEvidencia()
    {
        return $this->hasMany(VotoEvidencia::class, 'voter_user_id');
    }

    // --- Helpers de nivel ---

    public function getNombreNivelAttribute(): string
    {
        return match (true) {
            $this->puntos_totales >= 2000 => 'Héroe del Planeta',
            $this->puntos_totales >= 500  => 'Guardián',
            $this->puntos_totales >= 100  => 'Reciclador',
            default                       => 'Principiante',
        };
    }

    public function recalcularNivel(): void
    {
        $this->nivel = match (true) {
            $this->puntos_totales >= 2000 => 4,
            $this->puntos_totales >= 500  => 3,
            $this->puntos_totales >= 100  => 2,
            default                       => 1,
        };
        $this->save();
    }
}
