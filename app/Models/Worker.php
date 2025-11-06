<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Worker extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'phone',
        'has_whatsapp',
        'dni',
        'cuil_cuit',
        'tax_status',
        'skills',
        'dni_front_path',
        'dni_back_path',
    ];

    protected $casts = [
        'has_whatsapp' => 'boolean',
        'skills' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($worker) {
            // if ($worker->dni_front_path) {
            //     Storage::delete($worker->dni_front_path);
            // }
            if ($worker->dni_back_path) {
                Storage::delete($worker->dni_back_path);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function getDniFrontUrlAttribute()
    // {
    //     return $this->dni_front_path ? Storage::url($this->dni_front_path) : null;
    // }

    // public function getDniBackUrlAttribute()
    // {
    //     return $this->dni_back_path ? Storage::url($this->dni_back_path) : null;
    // }
}
