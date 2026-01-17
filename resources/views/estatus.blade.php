<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatus - Experiencia Recepcional</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; border: none; }
        .card-header { border-radius: 15px 15px 0 0 !important; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="text-center mb-4">
        <img src="{{ asset('img/logo.jpg') }}" alt="Logo UV" style="max-width: 80px;">
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white p-3">
            <h3 class="mb-0">Mi Estatus de Entrega</h3>
        </div>
        <div class="card-body p-4">
            <p class="text-muted">Aquí puedes consultar el estado de tus archivos subidos al sistema.</p>
            
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nombre del Archivo</th>
                        <th>Fecha de Subida</th>
                        <th>Estatus</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($misDocumentos as $doc)
                    <tr>
                        <td>{{ $doc->nombre_original }}</td>
                        <td>{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($doc->estatus == 'Aprobado')
                                <span class="badge bg-success">Revisado</span>
                            @elseif($doc->estatus == 'Rechazado')
                                <span class="badge bg-danger">Rechazado</span>
                            @else
                                <span class="badge bg-warning text-dark">Pendiente de Revisión</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ asset($doc->ruta_archivo) }}" target="_blank" class="btn btn-sm btn-outline-primary">Ver PDF</a>
                                
                                @if($doc->estatus != 'Aprobado')
                                <form action="/borrar-documento/{{ $doc->id }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres borrar este archivo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Borrar</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Aún no has subido ningún documento.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-4">
                <a href="/menu" class="btn btn-secondary">Volver al Menú</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>