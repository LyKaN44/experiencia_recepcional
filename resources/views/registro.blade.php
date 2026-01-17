<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Experiencia Recepcional</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { 
            background-color: #f8f9fa; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
        }
        .register-card { 
            max-width: 450px; 
            width: 100%; 
            margin: auto; 
            padding: 40px; 
            border-radius: 15px; 
            background: white; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.2); 
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="text-center mb-4">
        <img src="{{ asset('img/logo.jpg') }}" alt="Logo" style="max-width: 100px;">
    </div>

    <h3 class="text-center mb-4">Registro de Alumno</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/registro" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nombre Completo</label>
            <input type="text" name="name" class="form-control" placeholder="Ej: Nombre(s) Apellido Apellido" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Matrícula</label>
            <input type="text" name="matricula" class="form-control" placeholder="Ej: S22012345" required value="{{ old('matricula') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres" required>
        </div>

<div class="mb-3">
    <label class="form-label">Carrera</label>
    <select name="carrera" class="form-select" required>
        <option value="" selected disabled>Selecciona tu carrera</option>
        <option value="Sistemas Computacionales Administrativos">Sistemas Computacionales Administrativos</option>
        <option value="Contaduría">Contaduría</option>
        <option value="Administración de Empresas">Administración de Empresas</option>
        <option value="Gestión y Dirección de Negocios">Gestión y Dirección de Negocios</option>
    </select>
</div>

        <button type="submit" class="btn btn-success w-100 mb-3">Crear Cuenta</button>
        
        <div class="text-center">
            <a href="/" class="text-decoration-none text-muted">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </form>
</div>

</body>
</html>
