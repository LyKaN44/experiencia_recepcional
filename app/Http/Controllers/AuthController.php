<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

class AuthController extends Controller
{
    
    public function showRegistro()
    {
        return view('registro');
    }

   public function registro(Request $request)
{
    //validacion de contraseñas
    $request->validate([
        'nombre'    => 'required|string|max:100',
        'matricula' => 'required|string|max:9|unique:usuario,matricula_usuario',
        'correo'    => 'required|email|unique:usuario,correo_uv_usuario',
        
        'password'  => 'required|min:8|confirmed', 
    ], [
        
        'password.confirmed' => 'Las contraseñas no coinciden, verifícalas.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'matricula.unique' => 'Esta matrícula ya tiene una cuenta activa.',
    ]);

    
    $estudianteInscrito = DB::table('estudiante_inscrito')
                          ->where('matricula_estudiante_inscrito', $request->matricula)
                          ->first();

    if (!$estudianteInscrito) {
        return back()->withErrors([
            'matricula' => 'No se puede registrar porque no se encontró su inscripción vigente.'
        ])->withInput();
    }

    //Guardado con datos oficiales
    \App\Models\User::create([
        'matricula_usuario'  => $request->matricula, 
        'nombre_usuario'     => $request->nombre,
        'correo_uv_usuario'  => $request->correo,
        'password'           => bcrypt($request->password),
        'rol'                => 'ESTUDIANTE',
        'licenciatura'       => $estudianteInscrito->licenciatura_estudiante_inscrito,
        'fecha_registro'     => now(),
        'id_periodo_usuario' => $estudianteInscrito->id_periodo_estudiante_inscrito 
    ]);

    return redirect('/')->with('success', 'Usuario registrado correctamente. Ya puedes iniciar sesión.');
}

    public function login(Request $request)
    {
        $credentials = [
            'matricula_usuario' => $request->matricula,
            'password'          => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/menu');
        }

        return back()->withErrors(['matricula' => 'Credenciales incorrectas']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}