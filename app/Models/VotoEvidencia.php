<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotoEvidencia extends Model
{
    protected $table = 'votos_evidencia';

    protected $fillable = [
        'escaneo_id',
        'voter_user_id',
        'voto',
    ];

    public function escaneo()
    {
        return $this->belongsTo(Escaneo::class);
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_user_id');
    }
}
