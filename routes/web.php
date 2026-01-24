<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrabajoController;
use App\Models\TrabajoRecepcional;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// --- RUTAS PÚBLICAS (Sin Login) ---
Route::get('/', function () {
    return view('login'); 
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/registro', [AuthController::class, 'showRegistro']);
Route::post('/registro', [AuthController::class, 'registro']);
Route::post('/procesar-registro', [AuthController::class, 'registro']);

// --- RUTAS PROTEGIDAS (Solo usuarios logueados) ---
Route::middleware(['auth'])->group(function () {

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Menú principal con redirección por rol
    Route::get('/menu', function () {
        if (Auth::user()->rol === 'ADMINISTRADOR') {
            return redirect('/admin/panel');
        }
        return view('menu');
    });

    // --- Módulo de Estudiante ---
    Route::get('/registrar-trabajo', [TrabajoController::class, 'create']);
    Route::post('/guardar-trabajo', [TrabajoController::class, 'store']);
    Route::get('/descargar-comprobante', [TrabajoController::class, 'descargarPDF'])->name('descargar.pdf');

    Route::get('/estatus', function () {
        $user = Auth::user();
        $miTrabajo = DB::table('estudiante_tr')
            ->join('trabajo_recepcional', 'estudiante_tr.id_tr_registrado', '=', 'trabajo_recepcional.id_tr')
            ->join('docente', 'trabajo_recepcional.director_tr', '=', 'docente.id_docente')
            ->where('estudiante_tr.matricula_estudiante_tr', $user->matricula_usuario)
            ->select('trabajo_recepcional.*', 'docente.nombre_docente') 
            ->first();

        return view('estatus', compact('miTrabajo'));
    });

    // --- Módulo de Administración ---
    Route::get('/admin/panel', function () {
        if (Auth::user()->rol !== 'ADMINISTRADOR') {
            return redirect('/menu');
        }

        $carrerasFijas = [
            'ADMINISTRACION', 
            'CONTADURIA', 
            'GESTION Y DIRECCION DE NEGOCIOS',
            'SISTEMAS COMPUTACIONALES ADMINISTRATIVOS'
        ];

        // Eager Loading para evitar el error de "countable" y optimizar la carga
        $documentos = TrabajoRecepcional::with(['estudiantes', 'directorDocente'])->get();

        return view('admin_panel', compact('carrerasFijas', 'documentos'));
    });

    // Buscador de estudiantes (para colaboradores o registro)
    Route::get('/buscar-estudiante/{matricula}', function ($matricula) {
        $estudiante = DB::table('estudiante_inscrito')
                        ->where('matricula_estudiante_inscrito', $matricula)
                        ->first();

        if (!$estudiante) {
            return response()->json([
                'success' => false, 
                'message' => "La Coordinación no tiene registro de esta matrícula."
            ]);
        }

        $yaRegistrado = DB::table('usuario')
            ->where('matricula_usuario', $matricula)
            ->where('id_periodo_usuario', $estudiante->id_periodo_estudiante_inscrito)
            ->first();

        if ($yaRegistrado) {
            return response()->json([
                'success' => false, 
                'message' => "Ya se encuentra registrado. Por favor inicia sesión o contacta a la Coordinación."
            ]);
        }

        return response()->json([
            'success' => true,
            'nombre'  => $estudiante->nombre_estudiante_inscrito,
            'correo'  => $estudiante->correo_uv_estudiante_inscrito,
            'licenciatura_estudiante_inscrito' => $estudiante->licenciatura_estudiante_inscrito
        ]);
    });

    Route::post('/subir-formato-tr', [TrabajoController::class, 'subirFormatoRuta']);

    Route::post('/admin/validar-trabajo/{id}', [TrabajoController::class, 'validarTrabajo'])->name('admin.validar');
}); 