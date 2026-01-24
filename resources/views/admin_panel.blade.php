<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - UV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #e9effb; min-height: 100vh; margin: 0; }
        .sidebar-right {
            background-color: #005bb5;
            color: white;
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            width: 260px;
            padding: 30px 15px;
            z-index: 1000;
        }
        .sidebar-right a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .main-content { margin-right: 260px; padding: 40px; }
        .table-container { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .thead-uv { background-color: #005bb5; color: white; }
        .btn-validar { background-color: #2c75e1; color: white; border: none; font-size: 0.8rem; padding: 5px 8px; border-radius: 4px; transition: 0.3s; }
        .btn-validar:hover { background-color: #1a5bb8; }
        .btn-validar:disabled { background-color: #ccc; cursor: not-allowed; }
        .nav-tabs .nav-link { color: #005bb5; font-weight: 500; }
        .nav-tabs .nav-link.active { background-color: #005bb5; color: white; border-radius: 5px 5px 0 0; }
        
        /* Colores por estatus */
        .fila-validada { background-color: #d4edda !important; } /* Verde suave */
        .fila-por-validar { background-color: #fff3cd !important; } /* Amarillo suave */
    </style>
</head>
<body>

    <div class="sidebar-right">
        <div class="text-center mb-4">
            <h5>Bienvenido(a)</h5>
            <p class="small text-white-50">Administrador(a)</p>
            <hr>
            <p class="fw-bold">{{ Auth::user()->name }}</p>
        </div>
        <nav>
            <a href="/menu">Inicio</a>
            <a href="#">Agregar Usuario</a>
            <a href="/admin/panel" class="bg-white text-primary fw-bold" style="border-radius: 5px;">Trabajos Recepcionales</a>
            <form action="/logout" method="POST" class="mt-4">
                @csrf
                <button class="btn btn-link text-white text-decoration-none w-100 text-start p-2">Cerrar Sesión</button>
            </form>
        </nav>
    </div>

    <div class="main-content">
        <h2 class="text-center text-primary mb-4">Trabajos Recepcionales</h2>

        {{-- Mensaje de éxito tras validar --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-10">
                <input type="text" id="buscadorAdmin" class="form-control" placeholder="Buscar por nombre del trabajo o estudiante...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Buscar</button>
            </div>
        </div>

        <ul class="nav nav-tabs mb-3" id="carreraTabs" role="tablist">
            @foreach($carrerasFijas as $index => $carrera)
                <li class="nav-item">
                    <button class="nav-link {{ $index == 0 ? 'active' : '' }}" id="tab-{{ $index }}" data-bs-toggle="tab" data-bs-target="#content-{{ $index }}" type="button">
                        {{ $carrera }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content table-container">
            @foreach($carrerasFijas as $index => $carrera)
                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="content-{{ $index }}">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="thead-uv">
                            <tr>
                                <th>Validado</th>
                                <th>Nombre del Trabajo</th>
                                <th>Modalidad</th>
                                <th>Estudiante Responsable</th>
                                <th>Director / Tutor</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                       <tbody>
    @forelse($documentos->where('licenciatura', $carrera) as $doc)
        {{-- Forzamos el color de fondo si el estatus coincide --}}
        <tr class="{{ trim($doc->estatus_tr) == 'REGISTRO VALIDADO' ? 'fila-validada' : '' }}">
            
            <td class="fw-bold">
                {{-- Aquí es donde se ve el cambio visual --}}
                @if(trim($doc->estatus_tr) == 'REGISTRO VALIDADO')
                    <span class="text-success"><i class="bi bi-check-circle-fill"></i> ✓ VALIDADO</span>
                @else
                    <span class="text-danger"><i class="bi bi-x-circle-fill"></i> X PENDIENTE</span>
                @endif
            </td>

            <td class="small">{{ $doc->nombre_tr }}</td>
            <td>{{ $doc->modalidad }}</td>
            <td>{{ $doc->estudiantes->where('rol_estudiante_tr', 'RESPONSABLE')->first()->nombre_estudiante_tr ?? 'N/A' }}</td>
            <td>{{ $doc->directorDocente->nombre_docente ?? 'Sin director' }}</td>

            <td>
                <div class="d-flex flex-column gap-2">
                    @if($doc->archivo_formato_tr)
                        <a href="{{ asset('storage/' . $doc->archivo_formato_tr) }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="bi bi-file-earmark-pdf-fill"></i> VER PDF
                        </a>
                        
                        <form action="/admin/validar-trabajo/{{ $doc->id_tr }}" method="POST">
                            @csrf
                            {{-- Lógica del botón --}}
                            @if(trim($doc->estatus_tr) == 'REGISTRO VALIDADO')
                                <button type="submit" class="btn btn-secondary btn-sm w-100">
                                    INVALIDAR
                                </button>
                            @else
                                <button type="submit" class="btn-validar w-100">
                                    VALIDAR
                                </button>
                            @endif
                        </form>
                    @else
                        <span class="badge bg-light text-muted border">Sin archivo</span>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="6">No hay registros.</td></tr>
    @endforelse
</tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>