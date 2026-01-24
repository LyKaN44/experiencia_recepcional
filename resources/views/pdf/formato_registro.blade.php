<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10pt; line-height: 1.5; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .header { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        td { border: 1px solid #000; padding: 5px; }
        .label { background-color: #f2f2f2; width: 30%; font-weight: bold; }
        .footer-table { border: none; margin-top: 60px; width: 100%; table-layout: fixed; }
        .footer-table td { border: none; text-align: center; vertical-align: bottom; padding: 10px 5px; }
        .linea-firma { border-top: 1px solid #000; width: 90%; margin: 0 auto 5px auto; }
        .nombre-firma { font-size: 8pt; text-transform: uppercase; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="header text-center">
        <p class="bold" style="font-size: 14pt; margin: 0;">Universidad Veracruzana</p>
        <p class="bold" style="font-size: 11pt; margin: 5px 0;">{{ $documentTitle ?? 'Formato Registro de Director de trabajo recepcional y Tema' }}</p>
        {{-- Cambiamos el texto dependiendo de si hay colaboradores --}}
        <p class="bold">{{ count($estudiantes) > 1 ? 'TRABAJO RECEPCIONAL COLECTIVO' : 'TRABAJO RECEPCIONAL INDIVIDUAL' }}</p>
    </div>

    <table>
        <tr>
            <td class="label">FECHA</td>
            <td>{{ now()->format('d/m/Y') }}</td>
            <td class="label">Periodo de Inscripción</td>
            <td>{{ $trabajo->id_periodo_tr }}</td>
        </tr>
    </table>

    <table>
        {{-- Listamos a todos los estudiantes --}}
        @foreach($estudiantes as $index => $e)
            <tr>
                <td class="label">Estudiante {{ $index + 1 }} ({{ $e->rol_estudiante_tr }})</td>
                <td>{{ $e->nombre_estudiante_tr }} - {{ $e->matricula_estudiante_tr }}</td>
            </tr>
        @endforeach
        
        <tr><td class="label">Licenciatura</td><td>{{ $trabajo->licenciatura }}</td></tr>
        <tr><td class="label">Tema propuesto</td><td>{{ $trabajo->nombre_tr }}</td></tr>
        <tr><td class="label">Modalidad</td><td>{{ $trabajo->modalidad }}</td></tr>
        
        {{-- Mostramos contacto del responsable --}}
        @php $resp = $estudiantes->where('rol_estudiante_tr', 'RESPONSABLE')->first() ?? $estudiantes[0]; @endphp
        <tr><td class="label">Correo Electrónico</td><td>{{ $resp->correo_uv_estudiante_tr }}</td></tr>
        <tr><td class="label">Teléfono celular</td><td>{{ $resp->telefono_estudiante_tr }}</td></tr>
        
        <tr><td class="label">Propuesta Director</td><td>{{ $tutor->nombre_docente ?? 'SIN ASIGNAR' }}</td></tr>
    </table>

    {{-- Sección de Firmas Dinámica --}}
    <table class="footer-table">
        <tr>
            {{-- Generamos un espacio para cada estudiante --}}
            @foreach($estudiantes as $e)
                <td>
                    <div class="linea-firma"></div>
                    <div class="nombre-firma">{{ $e->nombre_estudiante_tr }}</div>
                    <div class="bold">Estudiante</div>
                </td>
            @endforeach

            {{-- Firma del Director --}}
            <td>
                <div class="linea-firma"></div>
                <div class="nombre-firma">{{ $tutor->nombre_docente ?? '________________' }}</div>
                <div class="bold">Director propuesto</div>
            </td>
        </tr>
    </table>
</body>
</html>