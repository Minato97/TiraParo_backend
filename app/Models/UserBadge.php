<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    protected $table = 'user_badges';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'badge_id',
        'obtenido_at',
    ];

    protected function casts(): array
    {
        return [
            'obtenido_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
