<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TrabajoRecepcional extends Model {
    protected $table = 'trabajo_recepcional';
    protected $primaryKey = 'id_tr';
    public $timestamps = false;
   protected $fillable = [
    'nombre_tr', 
    'modalidad', 
    'licenciatura', 
    'inscripcion', 
    'director_tr', 
    'id_periodo_tr', 
    'estatus_tr',
    'archivo_formato_tr' 
];

public function estudiantes()
{
    // Un trabajo tiene muchos estudiantes (responsable y colaborador)
    return $this->hasMany(EstudianteTR::class, 'id_tr_registrado', 'id_tr');
}
public function directorDocente()
    {
        return $this->belongsTo(Docente::class, 'director_tr', 'id_docente');
    }
    }




