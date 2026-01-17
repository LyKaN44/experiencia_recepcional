<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Estudiante - UV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { background-color: #0056b3; color: white; min-height: 100vh; padding: 20px; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .card-uv { border-top: 5px solid #0056b3; border-radius: 8px; }
        .date-badge { background-color: #0056b3; color: white; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 p-5">
            <h2 class="text-center text-primary mb-5">Fechas importantes para NO olvidar</h2>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm card-uv p-3">
                        <span class="date-badge mb-2">Del 24 al 28 de noviembre</span>
                        <p class="mb-1 fw-bold">Envío de documento con avances</p>
                        <small class="text-muted">Responsable: Estudiante</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm card-uv p-3">
                        <span class="date-badge mb-2">Del 1 al 4 de diciembre</span>
                        <p class="mb-1 fw-bold">Localizar jurado para revisión</p>
                        <small class="text-muted">Responsable: Estudiante</small>
                    </div>
                </div>
                </div>
        </div>

        <div class="col-md-3 sidebar shadow">
            <div class="text-center mb-4">
                <h4>Bienvenido(a)</h4>
                <p class="small text-white-50">Estudiante</p>
                <hr>
                <h5>{{ Auth::user()->name }}</h5>
            </div>
            <nav>
                <a href="/menu">Inicio</a>
                <a href="/registrar-trabajo">Registrar Trabajo Recepcional</a>
                <a href="/estatus">Ver Status</a>
                <form action="/logout" method="POST" class="mt-4">
                    @csrf
                    <button class="btn btn-link text-white p-0 text-decoration-none">Cerrar Sesión</button>
                </form>
            </nav>
        </div>
    </div>
</div>
</body>
</html>