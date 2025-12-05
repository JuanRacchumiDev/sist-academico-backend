<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaPrograma extends Model
{
    protected $table = "persona_programa";

    public $timestamps = true; 

    protected $fillable = [
        'id_persona',
        'id_programa'
    ];
}
