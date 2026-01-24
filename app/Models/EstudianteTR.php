<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EstudianteTR extends Model {
    protected $table = 'estudiante_tr';
    protected $primaryKey = 'id_estudiante_tr';
    public $timestamps = false;
    protected $fillable = ['matricula_estudiante_tr', 'nombre_estudiante_tr', 'correo_uv_estudiante_tr', 'correo_personal_estudiante_tr', 'telefono_estudiante_tr', 'rol_estudiante_tr', 'id_tr_registrado', 'id_periodo_estudiante_tr'];

    public function trabajo() {
        return $this->belongsTo(TrabajoRecepcional::class, 'id_tr_registrado', 'id_tr');
    }
}