<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estatus extends Model
{
    protected $table = 'estatus';

    protected $fillable = ['estatus'];

    public function users()
    {
        return $this->hasMany(User::class, 'estatus_id');
    }
}
