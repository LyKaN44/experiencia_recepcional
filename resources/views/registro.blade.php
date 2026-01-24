<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Estudiante - UV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #e9effb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .register-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .register-card { background: white; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; padding: 30px; }
        .uv-logo { max-width: 120px; margin-bottom: 20px; }
        .btn-uv { background-color: #005bb5; color: white; border-radius: 8px; font-weight: 600; }
        .btn-uv:hover { background-color: #004488; color: white; }
        .form-control[readonly] { background-color: #f0f4f8; cursor: not-allowed; }
        .section-title { color: #005bb5; font-weight: 700; border-bottom: 2px solid #f0f4f8; padding-bottom: 10px; margin-bottom: 20px; }
        label { font-size: 0.85rem; font-weight: 600; color: #555; }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-card">
        <div class="text-center">
            <img src="{{ asset('img/logo.jpg') }}" alt="Logo UV" class="uv-logo">
            <h4 class="section-title">Registro de Usuario</h4>
        </div>


        @if ($errors->any())
        <div class="alert alert-danger py-2" style="font-size: 0.85rem; border-radius: 8px;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <form action="/procesar-registro" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="matricula">Matrícula</label>
                <div class="input-group">                                                   
                    <input type="text" name="matricula" id="matricula" class="form-control" placeholder="Ej: S22010011" required maxlength="9">
                    <button class="btn btn-uv" type="button" id="btnBuscar">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                </div>
                <small class="text-muted">Busca tu matrícula para validar tu inscripción.</small>
            </div>

            <div class="mb-3">
                <label for="nombre">Nombre Completo</label>
                <input type="text" name="nombre" id="nombre" class="form-control" readonly required placeholder="Se llenará automáticamente">
            </div>

            <div class="mb-3">
                <label for="correo">Correo Institucional</label>
                <input type="email" name="correo" id="correo" class="form-control" readonly required placeholder="Se llenará automáticamente">
            </div>

            <div class="mb-3">
    <label for="licenciatura">Carrera / Licenciatura</label>
    <input type="text" name="licenciatura" id="licenciatura" class="form-control" readonly required placeholder="Se llenará automáticamente">
</div>

            <div class="mb-3">
    <label for="password">Contraseña</label>
    <input type="password" name="password" id="password" class="form-control" required placeholder="Mínimo 8 caracteres">
</div>

<div class="mb-3">
    <label for="password_confirmation">Confirmar Contraseña</label>
    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Repite tu contraseña">
@error('password')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="terminos" required>
    <label class="form-check-label small" for="terminos">
        Confirmo que los datos son correctos y coinciden con mi persona. Al mismo tiempo acepto las penalizaciones establecidas en la legislación universitaria al incumplir con el proceso de Experiencia Recepcional.
    </label>
</div>

<button type="submit" class="btn btn-uv w-100 py-2 mt-3" id="btnRegistrar" disabled>
    <i class="bi bi-person-plus-fill me-2"></i> Crear mi cuenta
</button>

            <div class="text-center mt-4">
                <a href="/" class="text-decoration-none small text-muted">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnBuscar = document.getElementById('btnBuscar');
    const btnRegistrar = document.getElementById('btnRegistrar');
    const inputMatricula = document.getElementById('matricula');
    const checkTerminos = document.getElementById('terminos'); // NUEVO

    // Función para controlar el estado del botón de registro
    function validarEstadoBoton() {
        const nombreCargado = document.getElementById('nombre').value !== "";
        const terminosAceptados = checkTerminos.checked;
        
        // El botón solo se activa si hay datos Y aceptó términos
        btnRegistrar.disabled = !(nombreCargado && terminosAceptados);
    }

    if (btnBuscar) {
        btnBuscar.addEventListener('click', function() {
            const matricula = inputMatricula.value;
            
            if (matricula.length < 5) {
                alert("Por favor, ingresa una matrícula válida.");
                return;
            }

            btnBuscar.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            btnBuscar.disabled = true;

            fetch('/buscar-estudiante/' + matricula)
                .then(response => response.json())
                .then(data => {
                    btnBuscar.innerHTML = '<i class="bi bi-search me-1"></i> Buscar';
                    btnBuscar.disabled = false;

                    if (data.success) {
                        document.getElementById('nombre').value = data.nombre;
                        document.getElementById('correo').value = data.correo;
                        
                        const carreraVal = data.licenciatura || data.licenciatura_estudiante_inscrito;
                        document.getElementById('licenciatura').value = carreraVal || "Dato no encontrado";
                        
                        // Si busca otra matrícula, desmarcamos el checkbox por seguridad
                        checkTerminos.checked = false; 
                        validarEstadoBoton();
                        
                        alert("Validación exitosa. Por favor acepta los términos para continuar.");
                    } else {
                        // Si falla, mostramos el mensaje personalizado que viene del servidor (el del periodo activo)
                        alert(data.message || "Matrícula no encontrada.");
                        document.getElementById('nombre').value = "";
                        document.getElementById('correo').value = "";
                        document.getElementById('licenciatura').value = "";
                        btnRegistrar.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    btnBuscar.innerHTML = '<i class="bi bi-search me-1"></i> Buscar';
                    btnBuscar.disabled = false;
                    alert("Error al conectar con el servidor.");
                });
        });
    }

    // Escuchar cambios en el checkbox para habilitar/deshabilitar el registro
    if (checkTerminos) {
        checkTerminos.addEventListener('change', validarEstadoBoton);
    }
});
</script>

</body>
</html>