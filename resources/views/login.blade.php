<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Trabajo Recepcional UV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body, html { height: 100%; margin: 0; }
        .row { height: 100vh; margin: 0; }
        
        /* Lado Izquierdo: Imagen de la Facultad */
        .bg-facultad {
            background-image: url("{{ asset('img/Facultad.jpeg') }}");
            background-size: cover;
            background-position: center;
            height: 100%;
        }

        /* Lado Derecho: Formulario */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            text-align: center;
        }

        .logo-sistema {
            max-width: 280px;
            margin-bottom: 20px;
        }

        .btn-uv {
            background-color: #007bff;
            border: none;
            padding: 12px;
            font-weight: bold;
        }

        .btn-uv:hover {
            background-color: #0056b3;
        }

        .footer-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-6 d-none d-md-block bg-facultad">
            </div>

        <div class="col-md-6 login-container">
            <div class="login-box">
                <img src="{{ asset('img/Sistema.png') }}" alt="Logo Sistema" class="logo-sistema">
                
                <h4 class="mb-4 fw-normal">Inicio de Sesión</h4>

                @if($errors->any())
                    <div class="alert alert-danger text-start">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="/login" method="POST">
                    @csrf
                    <div class="mb-3 text-start">
                        <label class="form-label text-muted small">Matrícula</label>
                        <input type="text" name="matricula" class="form-control form-control-lg" required autofocus>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label text-muted small">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-uv btn-lg">Iniciar Sesión</button>
                        <a href="/registro" class="btn btn-outline-secondary btn-lg" style="border-radius: 12px; font-size: 1rem;">
        ¿No tienes cuenta? Regístrate
    </a>
                    </div>
                </form>

        
                <div class="footer-text">
                    <p>Facultad de Contaduría y Administración, Región Xalapa</p>
                    <a href="#" class="text-decoration-none small">¿Olvidaste tu contraseña?</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>