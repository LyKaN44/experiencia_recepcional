<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    
    protected $table = 'tutores';

    protected $fillable = ['nombre'];

    
    public function alumnos()
    {
        return $this->hasMany(User::class, 'tutor_id');
    }
}