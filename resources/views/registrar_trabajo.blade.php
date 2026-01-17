<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Trabajo Recepcional - UV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #e9effb; min-height: 100vh; margin: 0; overflow-x: hidden; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Sidebar azul a la derecha conforme a la imagen de la UV */
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
            transition: 0.3s;
        }

        .sidebar-right a:hover { background-color: rgba(255,255,255,0.2); border-radius: 5px; }
        .sidebar-active { background-color: white !important; color: #005bb5 !important; font-weight: bold; border-radius: 5px; }

        /* Contenedor principal */
        .main-content { margin-right: 260px; padding: 40px; }

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            height: 100%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .section-title { color: #2c75e1; font-weight: 600; margin-bottom: 25px; border-bottom: 2px solid #f0f4f8; padding-bottom: 10px; }
        
        label { font-size: 0.85rem; color: #444; margin-bottom: 8px; font-weight: 500; }
        .form-control, .form-select { border-color: #dce4ec; font-size: 0.9rem; margin-bottom: 20px; border-radius: 8px; }
        .form-control:focus, .form-select:focus { border-color: #005bb5; box-shadow: 0 0 0 0.2rem rgba(0,91,181,0.1); }
    </style>
</head>
<body>

    <div class="sidebar-right shadow">
        <div class="text-center mb-4">
            <h5 class="mb-1">Bienvenido(a)</h5>
            <p class="small text-white-50">Estudiante</p>
            <hr class="bg-white">
            <p class="fw-bold">{{ Auth::user()->name }}</p>
        </div>
        
        <nav class="mt-4">
            <a href="/menu">Inicio</a>
            <a href="/registrar-trabajo" class="sidebar-active">Registrar Trabajo Recepcional</a>
            <a href="/estatus">Ver Status</a>
            
            <form action="/logout" method="POST" class="mt-5">
                @csrf
                <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start p-2" style="font-size: 0.9rem;">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </button>
            </form>
        </nav>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <h3 class="text-center mb-5" style="color: #2c3e50;">Registrar Trabajo Recepcional</h3>

            {{-- Formulario funcional conectado a tus rutas existentes --}}
            <form action="/guardar-tutor" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-card">
                            <h5 class="section-title">Información del trabajo</h5>
                            
                            <label>Nombre del trabajo</label>
                            <input type="text" name="titulo_trabajo" class="form-control" placeholder="Ingrese el título de su investigación">

                            <label>Modalidad</label>
                            <select name="modalidad" class="form-select">
                                <option value="">Seleccione una opción</option>
                                <option value="Tesis">Tesis</option>
                                <option value="Tesina">Tesina</option>
                                <option value="Monografía">Monografía</option>
                                <option value="Memoria">Memoria de pasantía</option>
                            </select>

                            <label>Programa educativo</label>
                            <select class="form-select" disabled>
                                <option>{{ Auth::user()->carrera }}</option>
                            </select>

                            <label>Tipo de inscripción</label>
                            <select class="form-select">
                                <option>Primera inscripción</option>
                                <option>Segunda inscripción</option>
                            </select>

                            <label>Archivo de registro (PDF)</label>
                            <input type="file" name="documento" class="form-control" accept=".pdf">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-card">
                            <h5 class="section-title">Dirección académica</h5>

                            <label>Nombre del director (Tutor)</label>
                            <select name="tutor_id" class="form-select" required>
                                <option value="">Seleccione a su tutor</option>
                                @foreach($tutores as $t)
                                    @php $cupos = $t->alumnos->count(); @endphp
                                    <option value="{{ $t->id }}" 
                                        {{ Auth::user()->tutor_id == $t->id ? 'selected' : '' }}
                                        {{ $cupos >= 3 && Auth::user()->tutor_id != $t->id ? 'disabled' : '' }}>
                                        {{ $t->nombre }} 
                                        ({{ $cupos }}/3 Cupos) 
                                        @if($cupos >= 3 && Auth::user()->tutor_id != $t->id) — AGOTADO @endif
                                    </option>
                                @endforeach
                            </select>

                            <label>Nombre del codirector</label>
                            <input type="text" class="form-control" placeholder="Nombre del docente (Opcional)">

                            <label>Cuerpo académico</label>
                            <input type="text" class="form-control" placeholder="Nombre del cuerpo académico">

                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="colaborador">
                                <label class="form-check-label" for="colaborador">
                                    Registrar estudiante(s) colaborador(es)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary px-5 py-2 shadow-lg" style="border-radius: 25px; font-weight: 600; background-color: #005bb5;">
                        Guardar Información
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>