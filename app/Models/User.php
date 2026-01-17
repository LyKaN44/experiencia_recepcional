<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'matricula',
        'carrera',
        'password',
        'role',     
        'tutor_id', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}