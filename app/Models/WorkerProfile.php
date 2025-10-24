<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'telefono',
        'tiene_whatsapp',
        'dni',
        'foto',
        'skills',
    ];

    protected $casts = [
        'skills' => 'array',
        'tiene_whatsapp' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
