<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = 'docente';
    protected $primaryKey = 'id_docente';
    public $timestamps = false;

    public function alumnos()
    {
        // Conecta Docente -> Trabajo -> EstudianteTR
        return $this->hasManyThrough(
            \App\Models\EstudianteTR::class,       // Lo que queremos contar
            \App\Models\TrabajoRecepcional::class, // La tabla de en medio
            'director_tr',                         // Llave en Trabajo
            'id_tr_registrado',                    // Llave en EstudianteTR
            'id_docente',                          // Llave en Docente
            'id_tr'                                // Llave en Trabajo
        );
    }

    public function trabajos()
{
    return $this->hasMany(TrabajoRecepcional::class, 'director_tr', 'id_docente');
}
}