<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Documento;
use Illuminate\Support\Facades\Auth;

// --- RUTAS PÚBLICAS (LOGIN Y REGISTRO) ---
Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::get('/registro', [AuthController::class, 'showRegistro']);
Route::post('/registro', [AuthController::class, 'registro']);
Route::post('/logout', [AuthController::class, 'logout']);

// --- RUTAS PROTEGIDAS (AUTH) ---
Route::middleware(['auth'])->group(function () {

    // Redirección inteligente al entrar a /menu
    Route::get('/menu', function () {
        if (Auth::user()->role === 'admin') {
            return redirect('/admin/panel');
        }
        return view('menu'); // Esta es la vista de los ICONOS
    });

    // NUEVA SECCIÓN: Registrar Trabajo (Agrupa Tutor y Carga de PDF)
    Route::get('/registrar-trabajo', function () {
        $tutores = Tutor::all();
        return view('registrar_trabajo', compact('tutores'));
    });

    // Acciones de Alumno
    Route::post('/subir-archivo', [AuthController::class, 'upload']);
    Route::post('/guardar-tutor', function (Request $request) {
        $user = Auth::user();
        $tutorId = $request->tutor_id;
        $alumnosInscritos = User::where('tutor_id', $tutorId)->count();

        if ($alumnosInscritos >= 3) {
            return back()->with('error', 'Lo sentimos, este catedrático ya no tiene cupo disponible.');
        }

        $user->tutor_id = $tutorId;
        $user->save();
        return redirect('/registrar-trabajo')->with('success', 'Tutor asignado con éxito!');
    });

   Route::get('/estatus', function () {
    $userNumericId = auth()->user()->getAttributes()['id']; 
    
    $misDocumentos = \App\Models\Documento::where('user_id', $userNumericId)->get();
    
    return view('estatus', compact('misDocumentos'));
})->middleware('auth');

    Route::delete('/borrar-documento/{id}', function ($id) {
        $doc = Documento::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if (File::exists(public_path($doc->ruta_archivo))) {
            File::delete(public_path($doc->ruta_archivo));
        }
        $doc->delete();
        return back()->with('success', 'Documento eliminado');
    });

    // --- RUTAS DE ADMINISTRADOR ---
    Route::get('/admin/panel', function () {
        if (Auth::user()->role !== 'admin') {
            return redirect('/menu');
        }

        $carrerasFijas = [
            'Administracion',
            'Contaduria',
            'Gestion y Direccion de Negocios',
            'Sistemas Computacionales Administrativos'
        ];

        // Cargamos documentos con usuario y tutor para evitar errores en la vista
        $documentos = Documento::with(['user.tutor'])->get();

        return view('admin_panel', compact('documentos', 'carrerasFijas'));
    });

    Route::post('/admin/cambiar-estatus/{id}', function (Request $request, $id) {
        $doc = Documento::findOrFail($id);
        $doc->estatus = $request->nuevo_estatus;
        $doc->save();
        return back()->with('success', 'Estatus actualizado');
    });

});

use App\Http\Controllers\TrabajoController;

// Cambia la ruta que tenías por esta:
Route::post('/guardar-tutor', [TrabajoController::class, 'store'])->middleware('auth');