<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    protected $table = 'plantilla';

    protected $guarded = ['id'];

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen',
        'path',
        'user_crea',
        'user_actualiza',
        'user_elimina',
        'estado'
    ];

    protected $hidden = [
        'user_crea',
        'user_actualiza',
        'user_elimina',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    // public function getFileUrlAttribute(): string
    // {
    //     return $this->path ? Storage::url($this->path) : '';
    // }
}
