<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Documento;

class AuthController extends Controller {
    public function login(Request $request) {
    $credentials = $request->only('matricula', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        if (Auth::user()->role === 'admin') {
            return redirect()->intended('/admin/panel'); 
        }

        return redirect()->intended('/menu'); 
    }

    return back()->withErrors(['error' => 'Matrícula o contraseña incorrectos']);
}

    public function logout(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
}

public function showRegistro() {
    return view('registro');
}

public function registro(Request $request) {
   
    $request->validate([
        'name' => 'required|string|max:255',
        'matricula' => 'required|string|unique:users',
        'password' => 'required|string|min:3',
    ]);

    
    User::create([
    'name' => $request->name,
    'matricula' => $request->matricula,
    'carrera' => $request->carrera, // <-- Nueva línea
    'password' => Hash::make($request->password),
]);

    return redirect('/')->with('success', 'Usuario registrado. Ya puedes iniciar sesión.');
}


public function upload(Request $request) {
        $request->validate([
            'documento' => 'required|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('documento')) {
            $archivo = $request->file('documento');
            
            
            $matricula = Auth::user()->matricula;
            $nombreArchivo = $matricula . '_' . time() . '.pdf';
            
           
            $archivo->move(public_path('documentos'), $nombreArchivo);

            
            Documento::create([
                'user_id' => Auth::id(),
                'nombre_original' => $archivo->getClientOriginalName(),
                'ruta_archivo' => 'documentos/' . $nombreArchivo,
            ]);

            return back()->with('success', '¡Tesis guardada en servidor y base de datos!');
        }
    }


}