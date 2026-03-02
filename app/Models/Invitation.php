<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $casts = [
    'expired_at' => 'datetime',
];
    protected $fillable = [
        'colocation_id',
        'email',
        'token',
        'status',
        'expired_at',
    ];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }
}
