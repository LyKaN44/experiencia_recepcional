<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrabajoRecepcional;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function panel()
    {
        // 1. Definimos las carreras para las pestañas de tu Blade
        $carrerasFijas = [
            'ADMINISTARCIÓN', 
            'CONTADURÍA', 
            'GESTIÓN Y DIRECCIÓND E NEGOCIOS',
            'SISTEMAS COMPUTACIONALES ADMINISTRATIVOS'
        ];

        // 2. Traemos los trabajos con sus alumnos y tutor
        // "estudiantes" y "directorDocente" deben ser relaciones en tu modelo TrabajoRecepcional
        $documentos = TrabajoRecepcional::with(['estudiantes', 'directorDocente'])->get();

        // 3. Mandamos la "munición" a la vista
        return view('admin_panel', compact('carrerasFijas', 'documentos'));
    }

    public function validarTrabajo($id)
    {
        $trabajo = TrabajoRecepcional::findOrFail($id);
        
        // Switch de estatus: si está registrado lo valida, y viceversa
        $nuevoEstatus = ($trabajo->estatus_tr == 'VALIDADO') ? 'REGISTRADO' : 'VALIDADO';
        
        $trabajo->update(['estatus_tr' => $nuevoEstatus]);

        return back()->with('success', 'Estatus actualizado correctamente.');
    }
}