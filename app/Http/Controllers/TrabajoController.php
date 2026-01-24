<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrabajoRecepcional;
use App\Models\EstudianteTR;
use App\Models\Docente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TrabajoController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validaciones de formulario
        $request->validate([
            'titulo_trabajo' => 'required|string|max:500',
            'modalidad'      => 'required|in:MONOGRAFÍA,TESIS,TESINA',
            'tutor_id'       => 'required|exists:docente,id_docente',
            'inscripcion'    => 'required|in:1ERA INSCRIPCIÓN,2DA INSCRIPCIÓN',
            'correo_personal' => 'required|email',
            'telefono'       => 'required|string|min:10|max:15',
           'colab_matricula'   => 'nullable|array',
    'colab_matricula.*' => 'required_if:tiene_colaborador,1|string|max:9',
    'colab_nombre'      => 'nullable|array',
    'colab_nombre.*'    => 'required_if:tiene_colaborador,1|string',
            'colab_email'     => 'required_if:tiene_colaborador,1',
        ]);

        // 2. VALIDACIÓN DE LISTA BLANCA (Estudiante Inscrito)
        // Verificamos que el alumno logueado esté en la lista oficial del periodo
        $estudianteInscrito = DB::table('estudiante_inscrito')
            ->where('matricula_estudiante_inscrito', Auth::user()->matricula_usuario)
            ->where('id_periodo_estudiante_inscrito', Auth::user()->id_periodo_usuario)
            ->first();

        if (!$estudianteInscrito) {
            return back()->withInput()->withErrors([
                'error' => 'No estás autorizado. Tu matrícula no figura en la lista de inscritos de Experiencia Recepcional.'
            ]);
        }

        // Si hay colaborador, también validamos que esté en la lista oficial
        if ($request->tiene_colaborador == 1) {
            $colabInscrito = DB::table('estudiante_inscrito')
                ->where('matricula_estudiante_inscrito', $request->colab_matricula)
                ->where('id_periodo_estudiante_inscrito', Auth::user()->id_periodo_usuario)
                ->exists();

            if (!$colabInscrito) {
                return back()->withInput()->withErrors([
                    'error' => 'El colaborador indicado no está inscrito oficialmente en este periodo.'
                ]);
            }
        }

        // 3. Lógica de Cupo: Máximo 3 proyectos por docente
        $trabajosOcupados = TrabajoRecepcional::where('director_tr', $request->tutor_id)
            ->where('id_periodo_tr', Auth::user()->id_periodo_usuario)
            ->count();

        if ($trabajosOcupados >= 3) {
            return back()->withInput()->withErrors([
                'error' => 'El docente seleccionado ya tiene sus 3 trabajos cubiertos.'
            ]);
        }

        try {
            DB::beginTransaction();

            // 4. Crear el registro del Trabajo (Usando la licenciatura de la tabla oficial)
            $trabajo = TrabajoRecepcional::create([
                'nombre_tr'      => $request->titulo_trabajo,
                'modalidad'      => $request->modalidad,
                'licenciatura'   => $estudianteInscrito->licenciatura_estudiante_inscrito,
                'inscripcion'    => $request->inscripcion,
                'director_tr'    => $request->tutor_id,
                'id_periodo_tr'  => Auth::user()->id_periodo_usuario,
                'estatus_tr'     => 'REGISTRADO'
            ]);

            // 5. Crear el registro del Alumno Responsable
            $alumnoResponsable = EstudianteTR::create([
                'matricula_estudiante_tr' => Auth::user()->matricula_usuario,
                'nombre_estudiante_tr'    => Auth::user()->nombre_usuario,
                'correo_uv_estudiante_tr' => Auth::user()->correo_uv_usuario,
                'correo_personal_estudiante_tr' => $request->correo_personal, 
                'telefono_estudiante_tr'  => $request->telefono,
                'rol_estudiante_tr'       => 'RESPONSABLE',
                'id_tr_registrado'        => $trabajo->id_tr, 
                'id_periodo_estudiante_tr' => Auth::user()->id_periodo_usuario
            ]);

            // 6. Registro de Colaborador (opcional)
          // 6. Registro de Colaboradores (Múltiples de forma automática)
if ($request->tiene_colaborador == 1 && $request->has('colab_matricula')) {
    foreach ($request->colab_matricula as $key => $matricula) {
        // El $key es el índice (0, 1, 2...). 
        // Así nos aseguramos de que el nombre 0 vaya con la matrícula 0.
        if (!empty($matricula)) {
            EstudianteTR::create([
                'matricula_estudiante_tr' => $matricula,
                'nombre_estudiante_tr'    => $request->colab_nombre[$key],
                'correo_uv_estudiante_tr' => $request->colab_email[$key],
                'correo_personal_estudiante_tr' => $request->colab_correo_personal[$key] ?? null,
                'telefono_estudiante_tr'  => $request->colab_telefono[$key] ?? 0,
                'rol_estudiante_tr'       => 'COLABORADOR',
                'id_tr_registrado'        => $trabajo->id_tr,
                'id_periodo_estudiante_tr'=> Auth::user()->id_periodo_usuario
            ]);
        }
    }
}

            // 7. Registro en Seguimiento
            DB::table('seguimiento_tr')->insert([
                'id_tr_registrado'   => $trabajo->id_tr,
                'accion_tr'          => 'REGISTRADO', 
                'fecha_accion'       => now(),
                'id_usuario_realiza' => Auth::user()->id_usuario,
                'ruta_formato'       => null,
                'archivo'            => null
            ]);

            DB::commit();

            // 8. Generar PDF automáticamente al terminar el registro
            $tutor = Docente::find($request->tutor_id);
            $data = [
                'trabajo' => $trabajo,
                'alumno'  => $alumnoResponsable,
                'tutor'   => $tutor
            ];

            $pdf = Pdf::loadView('pdf.formato_registro', $data);
            return $pdf->download('Registro_TR_' . Auth::user()->matricula_usuario . '.pdf');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

   public function create()
{
    
    $datosOficiales = DB::table('estudiante_inscrito')
        ->where('matricula_estudiante_inscrito', Auth::user()->matricula_usuario)
        ->first();

    $tutores = Docente::withCount('alumnos as trabajos_count')->get();
    
    return view('registrar_trabajo', compact('tutores', 'datosOficiales'));
}

   public function descargarPDF()
{
    // 1. Buscamos el trabajo más reciente donde el usuario esté involucrado
    // Usamos with() para cargar de una vez los estudiantes y ahorrar consultas (Eager Loading)
    $trabajo = TrabajoRecepcional::with('estudiantes')
        ->whereHas('estudiantes', function($query) {
            $query->where('matricula_estudiante_tr', Auth::user()->matricula_usuario);
        })
        ->orderByDesc('id_tr')
        ->first();

    // 2. Si no hay trabajo, mandamos el error de regreso
    if (!$trabajo) {
        return back()->withErrors(['error' => 'No tienes un trabajo registrado aún.']);
    }

    // 3. Traemos a TODOS los estudiantes relacionados (Responsable + Colaboradores)
    // Al usar ->get() nos aseguramos de que sea una colección "Countable" para el PDF
    $estudiantes = EstudianteTR::where('id_tr_registrado', $trabajo->id_tr)->get();
        
    // 4. Buscamos al docente (tutor/director)
    $tutor = Docente::find($trabajo->director_tr);

    
    $data = [
        'trabajo'     => $trabajo,
        'estudiantes' => $estudiantes, 
        'tutor'       => $tutor,
        'fecha'       => now()->format('d/m/Y'),
        'documentTitle' => 'Formato Registro de Director de Trabajo Recepcional y Tema'
    ];

    




  
    $pdf = Pdf::loadView('pdf.formato_registro', $data);
    
   
    return $pdf->download('Comprobante_UV_' . Auth::user()->matricula_usuario . '.pdf');
}

public function subirFormatoRuta(Request $request)
{
    
    $request->validate([
        'pdf_formato' => 'required|mimes:pdf|max:2048', 
        'id_tr' => 'required|exists:trabajo_recepcional,id_tr'
    ]);

    if ($request->hasFile('pdf_formato')) {
        $trabajo = TrabajoRecepcional::find($request->id_tr);
        
        
        $ruta = $request->file('pdf_formato')->store('formatos_tr', 'public');

        
        $trabajo->update([
            'archivo_formato_tr' => $ruta, 
            'estatus_tr' => 'CON FORMATO DE REGISTRO'
        ]);

        return back()->with('success', '¡Ya quedó! Archivo subido y estatus actualizado.');
    }
}

public function validarTrabajo($id)
{
    // Buscamos el registro
    $trabajo = \App\Models\TrabajoRecepcional::findOrFail($id);

    // Asignación directa para asegurar el cambio
    $trabajo->estatus_tr = 'REGISTRO VALIDADO';
    $trabajo->save(); 

   

    return back()->with('success', '¡Estatus actualizado con éxito!');
}
}