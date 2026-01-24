<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'matricula_usuario', 'nombre_usuario', 'correo_uv_usuario', 
        'password', 'rol', 'licenciatura', 'fecha_registro', 'id_periodo_usuario'
    ];

    protected $hidden = ['password'];

    public function getAuthIdentifierName() {
        return 'matricula_usuario';
    }

    

    // Esto hace que {{ Auth::user()->name }} devuelva el nombre de la tabla
    public function getNameAttribute()
    {
        return $this->nombre_usuario;
    }

    // Esto te permite usar {{ Auth::user()->carrera }} en las vistas
    public function getCarreraAttribute()
    {
        return $this->licenciatura;
    }
}
