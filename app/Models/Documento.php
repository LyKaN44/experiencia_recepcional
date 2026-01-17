<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    
    protected $fillable = ['user_id', 'nombre_original', 'ruta_archivo', 'estatus'];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}