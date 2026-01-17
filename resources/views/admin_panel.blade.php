<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - UV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #e9effb; min-height: 100vh; margin: 0; }
        
        /* Sidebar azul a la derecha */
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

        /* Contenido Principal */
        .main-content { margin-right: 260px; padding: 40px; }

        /* Estilo de la Tabla y Buscador */
        .table-container { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .thead-uv { background-color: #005bb5; color: white; }
        .btn-validar { background-color: #2c75e1; color: white; border: none; font-size: 0.8rem; padding: 2px 8px; border-radius: 4px; }
        
        .nav-tabs .nav-link { color: #005bb5; font-weight: 500; }
        .nav-tabs .nav-link.active { background-color: #005bb5; color: white; border-radius: 5px 5px 0 0; }
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

        <div class="row mb-4">
            <div class="col-md-10">
                <input type="text" class="form-control" placeholder="Buscar por nombre del trabajo, del estudiante o del director...">
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
                                <th>Nombre del Estudiante</th>
                                <th>Nombre del Director</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documentos->where('user.carrera', $carrera) as $doc)
                                <tr>
                                    <td class="fw-bold {{ $doc->estatus == 'aprobado' ? 'text-success' : 'text-danger' }}">
                                        {{ $doc->estatus == 'aprobado' ? '✓' : 'X' }}
                                    </td>
                                    <td>{{ $doc->titulo ?? 'Sin título' }}</td>
                                    <td>{{ $doc->modalidad ?? 'N/A' }}</td>
                                    <td>{{ $doc->user->name }}</td>
                                    <td>{{ $doc->user->tutor->nombre ?? 'Sin tutor' }}</td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <a href="{{ asset($doc->ruta_archivo) }}" class="btn btn-danger btn-sm p-0" target="_blank">PDF</a>
                                            <form action="/admin/cambiar-estatus/{{ $doc->id }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="nuevo_estatus" value="{{ $doc->estatus == 'aprobado' ? 'pendiente' : 'aprobado' }}">
                                                <button type="submit" class="btn-validar">Validar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-muted">No hay trabajos registrados en esta carrera.</td>
                                </tr>
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