<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'celular',
        'pais_ciudad',
        'boleta',
    ];
}

