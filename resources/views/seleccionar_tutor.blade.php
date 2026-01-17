<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Tutor - UV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f0f2f5; }
        .card { border-radius: 15px; border: none; }
        .btn-success { border-radius: 10px; padding: 10px 20px; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo UV" style="max-width: 70px;">
                <h2 class="mt-3">Elección de Asesor</h2>
            </div>

            <div class="card shadow-lg">
                <div class="card-body p-4">
                    <p class="text-muted">Selecciona al profesor que dirigirá tu trabajo recepcional. 
                    <strong>Nota:</strong> Cada profesor tiene un cupo máximo de 3 alumnos.</p>

                    {{-- Alertas de error o éxito --}}
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="/guardar-tutor" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="tutor_id" class="form-label fw-bold">Lista de Catedráticos:</label>
                            <select name="tutor_id" id="tutor_id" class="form-select form-select-lg" required>
                                <option value="">-- Elige un tutor --</option>
                                @foreach($tutores as $t)
                                    {{-- Contamos cuántos alumnos tiene ya este tutor --}}
                                    @php $cupos = $t->alumnos->count(); @endphp
                                    
                                    <option value="{{ $t->id }}" {{ $cupos >= 3 ? 'disabled' : '' }}>
                                        {{ $t->nombre }} 
                                        ({{ $cupos }}/3 Cupos utilizados) 
                                        @if($cupos >= 3) — ¡CUPO LLENO! @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">Confirmar Asesoría</button>
                            <a href="/menu" class="btn btn-link text-decoration-none text-muted">Volver al menú</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-4 text-center">
                <small class="text-muted">Si el profesor que buscas no aparece o está lleno, contacta a la dirección.</small>
            </div>
        </div>
    </div>
</div>

</body>
</html>