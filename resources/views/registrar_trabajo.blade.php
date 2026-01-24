<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Trabajo Recepcional - UV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #e9effb; min-height: 100vh; margin: 0; overflow-x: hidden; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
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
        
        #seccionColaborador {
            transition: all 0.3s ease-in-out;
            border-left: 4px solid #005bb5;
        }
    </style>
</head>
<body>

    <div class="sidebar-right shadow">
        <div class="text-center mb-4">
            <h5 class="mb-1">Bienvenido(a)</h5>
            <p class="small text-white-50">Estudiante</p>
            <hr class="bg-white">
            <p class="fw-bold">{{ Auth::user()->nombre_usuario }}</p>
        </div>
        
        <nav class="mt-4">
            <a href="/menu">Inicio</a>
            <a href="/registrar-trabajo" class="sidebar-active">Registrar Trabajo Recepcional</a>
            <a href="/estatus">Ver Estatus</a>
            
            <form action="/logout" method="POST" class="mt-5">
                @csrf
                <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start p-2">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </button>
            </form>
        </nav>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <h3 class="text-center mb-5" style="color: #2c3e50;">Registrar Trabajo Recepcional</h3>

            @if($errors->has('error'))
                <div class="alert alert-danger">{{ $errors->first('error') }}</div>
            @endif

            <form action="/guardar-trabajo" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-card">
                            <h5 class="section-title">Información del trabajo</h5>
                            
                            <label>Nombre del trabajo</label>
                            <input type="text" name="titulo_trabajo" class="form-control" placeholder="Ingrese el título" required value="{{ old('titulo_trabajo') }}">

                            <label>Modalidad</label>
                            <select name="modalidad" class="form-select" required>
                                <option value="">Seleccione una opción</option>
                                <option value="MONOGRAFÍA" {{ old('modalidad') == 'MONOGRAFÍA' ? 'selected' : '' }}>Monografía</option>
                                <option value="TESINA" {{ old('modalidad') == 'TESINA' ? 'selected' : '' }}>Tesina</option>
                                <option value="TESIS" {{ old('modalidad') == 'TESIS' ? 'selected' : '' }}>Tesis</option>                               
                            </select>

                            <label>Tipo de inscripción</label>
                            <select class="form-select" name="inscripcion" required>
                                <option value="1ERA INSCRIPCIÓN">1era Inscripción</option>
                                <option value="2DA INSCRIPCIÓN">2da Inscripción</option>
                            </select>

                            <label>Correo Electrónico Personal</label>
                            <input type="email" name="correo_personal" class="form-control" placeholder="ejemplo@gmail.com" required value="{{ old('correo_personal') }}">

                            <label>Teléfono de Contacto (10 dígitos)</label>
                            <input type="tel" name="telefono" class="form-control" placeholder="2281234567" required maxlength="10">

                            
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-card shadow">
                            <h5 class="section-title">Dirección académica</h5>

                            <label>Nombre del director (Tutor)</label>
<select name="tutor_id" class="form-select" required>
    <option value="">Seleccione a su tutor</option>
    @foreach($tutores as $t)
        <option value="{{ $t->id_docente }}">
            {{ $t->nombre_docente }} ({{ $t->trabajos_count ?? 0 }}/3 Cupos)
        </option>
    @endforeach
</select>

                            <label>Nombre del codirector (Opcional)</label>
                            <input type="text" name="codirector" class="form-control" placeholder="Nombre del docente">

                            <div class="form-check mt-4 mb-3">
    <input class="form-check-input" type="checkbox" id="checkColaborador" name="tiene_colaborador" value="1">
    <label class="form-check-label fw-bold" for="checkColaborador">
        Registrar estudiante colaborador
    </label>
</div>
<div id="seccionColaborador" style="display: none;" class="bg-light p-3 rounded shadow-sm">
    <div id="contenedor-colaboradores">
        <div class="colaborador-bloque mb-4 p-2 border-bottom">
            <h6>Colaborador 1</h6>
            <label>Matrícula Colaborador</label>
            <div class="input-group mb-2">
                <input type="text" name="colab_matricula[]" class="form-control colab-matricula" placeholder="S22015741">
                <button class="btn btn-primary btn-buscar-colab" type="button">Buscar</button>
            </div>

            <label>Nombre completo</label>
            <input type="text" name="colab_nombre[]" class="form-control mb-2 colab-nombre" readonly>

            <label>Correo institucional</label>
            <input type="text" name="colab_email[]" class="form-control mb-2 colab-email" readonly>

            <label>Correo personal</label>
            <input type="email" name="colab_correo_personal[]" class="form-control mb-2" placeholder="ejemplo@gmail.com">

            <label>Teléfono</label>
            <input type="tel" name="colab_telefono[]" class="form-control mb-0" placeholder="2281234567">
        </div>
    </div>

   <button type="button" id="btn-añadir-otro" class="btn btn-outline-primary btn-sm mt-3">
        <i class="bi bi-plus-circle"></i> Añadir otro colaborador
    </button>
</div>

    
                
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary px-5 py-2 shadow-lg" style="border-radius: 25px; font-weight: 600; background-color: #005bb5;">
                        Guardar Información
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('checkColaborador');
    const seccion = document.getElementById('seccionColaborador');
    const contenedor = document.getElementById('contenedor-colaboradores');
    const btnAñadir = document.getElementById('btn-añadir-otro');
    let contador = 1;

    // --- 1. MOSTRAR/OCULTAR SECCIÓN ---
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            seccion.style.display = 'block';
        } else {
            seccion.style.display = 'none';
            // Limpiar datos si se arrepienten y desmarcan
            contenedor.querySelectorAll('input').forEach(i => i.value = '');
            // Si quieres que al desmarcar se borren los extras, descomenta abajo:
            /*
            while (contenedor.children.length > 1) {
                contenedor.lastChild.remove();
            }
            contador = 1;
            btnAñadir.style.display = 'block';
            */
        }
    });

    // --- 2. FUNCIÓN PARA AÑADIR OTRO COLABORADOR ---
    btnAñadir.addEventListener('click', function() {
        if (contador >= 2) return; // Seguridad

        contador++;
        
        // Clonamos el primer bloque (el que ya tienes en el HTML)
        const nuevo = document.querySelector('.colaborador-bloque').cloneNode(true);
        
        // Limpiamos los textos y valores del clon
        nuevo.querySelector('h6').innerText = `Colaborador ${contador}`;
        nuevo.querySelectorAll('input').forEach(i => i.value = '');
        
        // Quitamos cualquier botón de eliminar que se haya clonado antes (por si acaso)
        const botonesEliminarPrevios = nuevo.querySelectorAll('.btn-eliminar-colab');
        botonesEliminarPrevios.forEach(b => b.remove());

        // Creamos el botón de ELIMINAR para este nuevo bloque
        const btnBorrar = document.createElement('button');
        btnBorrar.className = 'btn btn-danger btn-sm mt-2 btn-eliminar-colab';
        btnBorrar.innerHTML = '<i class="bi bi-trash"></i> Eliminar colaborador';
        
        btnBorrar.onclick = function() {
            nuevo.remove();
            contador--;
            // Si bajamos del límite, volvemos a mostrar el botón de añadir
            if (contador < 2) {
                btnAñadir.style.display = 'block';
            }
        };
        
        nuevo.appendChild(btnBorrar);
        contenedor.appendChild(nuevo);

        // Si llegamos al límite (2 colaboradores extra), ocultamos el botón de añadir
        if (contador >= 2) {
            btnAñadir.style.display = 'none';
        }
    });

    // --- 3. DELEGACIÓN DE EVENTOS PARA EL BOTÓN BUSCAR ---
    // Esto permite que el botón "Buscar" funcione en bloques clonados
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('btn-buscar-colab')) {
            const bloque = e.target.closest('.colaborador-bloque');
            const inputMatricula = bloque.querySelector('.colab-matricula');
            const matricula = inputMatricula.value;

            if (!matricula) {
                alert("Por favor, ingresa una matrícula.");
                return;
            }

            // Efecto visual de carga
            const originalText = e.target.innerHTML;
            e.target.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            e.target.disabled = true;

            fetch(`/buscar-estudiante/${matricula}`)
                .then(res => res.json())
                .then(data => {
                    e.target.innerHTML = originalText;
                    e.target.disabled = false;

                    if (data.success) {
                        bloque.querySelector('.colab-nombre').value = data.nombre;
                        bloque.querySelector('.colab-email').value = data.correo;
                        // Opcional: poner el foco en el siguiente campo
                        bloque.querySelector('input[name="colab_correo_personal[]"]').focus();
                    } else {
                        alert(data.message);
                        bloque.querySelector('.colab-nombre').value = "";
                        bloque.querySelector('.colab-email').value = "";
                    }
                })
                .catch(err => {
                    e.target.innerHTML = originalText;
                    e.target.disabled = false;
                    console.error("Error en Fetch:", err);
                    alert("Error al conectar con el servidor.");
                });
        }
    });
});
</script>

</body>
</html>