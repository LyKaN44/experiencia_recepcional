<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Documento;
use Illuminate\Support\Facades\Hash;

class TrabajoController extends Controller
{
    public function store(Request $request)
    {
        
        $request->validate([
            'titulo_trabajo' => 'required|string|max:255',
            'modalidad' => 'required',
            'tutor_id' => 'required',
            'documento' => 'required|mimes:pdf|max:5120',
            
            'colab_matricula' => 'required_if:tiene_colaborador,1',
            'colab_nombre' => 'required_if:tiene_colaborador,1',
            'colab_email' => 'required_if:tiene_colaborador,1',
            'colab_telefono' => 'required_if:tiene_colaborador,1',
        ]);

        $user = auth()->user();

        
        if ($request->hasFile('documento')) {
            $file = $request->file('documento');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
           
            $path = $file->move(public_path('documentos'), $nombreArchivo);
            $rutaRelativa = 'documentos/' . $nombreArchivo;

            Documento::create([
                'user_id' => $user->getAttributes()['id'], 
                'nombre_original' => $file->getClientOriginalName(),
                'ruta_archivo' => $rutaRelativa,
                'estatus' => 'Pendiente',
            ]);
        }

        // 3. ACTUALIZAR AL USUARIO (Si quieres guardar los datos del colab en la tabla users)
        // Nota: Asegúrate de que estas columnas existan en DBeaver o quita esta parte
        /*
        $user->update([
            'tutor_id' => $request->tutor_id,
            // ... otros campos
        ]);
        */

        return redirect('/menu')->with('success', '¡Registro exitoso!');
    }
}