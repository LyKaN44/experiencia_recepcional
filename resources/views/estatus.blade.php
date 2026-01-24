<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatus - Experiencia Recepcional</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #e9effb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card-header { border-radius: 15px 15px 0 0 !important; }
        .table th { background-color: #f8f9fa; color: #495057; font-weight: 600; width: 30%; }
        .badge-custom { padding: 8px 12px; border-radius: 8px; font-size: 0.85rem; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="text-center mb-4">
        <img src="{{ asset('img/logo.jpg') }}" alt="Logo UV" style="max-width: 150px;">
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white p-3 text-center">
                    <h3 class="mb-0">Mi Estatus de Experiencia Recepcional</h3>
                </div>
                <div class="card-body p-4">
                    
                    @if($miTrabajo)
                        <p class="text-muted mb-4">A continuación se detallan los datos generales de tu registro actual.</p>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th><i class="bi bi-journal-bookmark-fill me-2"></i>Título del Trabajo:</th>
                                        <td>{{ $miTrabajo->nombre_tr }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="bi bi-person-badge me-2"></i>Director / Tutor:</th>
                                        <td>{{ $miTrabajo->nombre_docente }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="bi bi-layers me-2"></i>Modalidad:</th>
                                        <td><span class="badge bg-secondary badge-custom">{{ $miTrabajo->modalidad }}</span></td>
                                    </tr>
                                    <tr>
                                        <th><i class="bi bi-info-circle me-2"></i>Estatus Actual:</th>
                                        <td>
                                            <span class="badge {{ $miTrabajo->estatus_tr == 'REGISTRADO' ? 'bg-success' : 'bg-warning' }} badge-custom text-uppercase">
                                                {{ $miTrabajo->estatus_tr }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-light border mt-3">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Tu información ha sido guardada correctamente. Cualquier cambio será reflejado en este apartado.
                            </small>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-folder-x text-muted" style="font-size: 4rem;"></i>
                            <h4 class="mt-3">Sin registro disponible</h4>
                            <p class="text-muted">Aún no has dado de alta tu trabajo recepcional en este periodo.</p>
                            <a href="/registrar-trabajo" class="btn btn-primary px-4 mt-2">
                                <i class="bi bi-plus-circle me-2"></i>Registrar ahora
                            </a>
                        </div>
                    @endif
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="/menu" class="btn btn-outline-secondary px-4 text-decoration-none">
                            <i class="bi bi-arrow-left me-2"></i>Volver al Menú
                        </a>
                        @if($miTrabajo->estatus_tr == 'REGISTRADO')
        <form action="/subir-formato-tr" method="POST" enctype="multipart/form-data" class="d-inline">
            @csrf
            <input type="hidden" name="id_tr" value="{{ $miTrabajo->id_tr }}">
            <input type="file" name="pdf_formato" accept="application/pdf" required class="form-control form-control-sm mb-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-upload"></i> Subir Formato Firmado
            </button>
        </form>
    @else
        <a href="{{ route('descargar.pdf') }}" class="btn btn-light border">
            <i class="bi bi-file-pdf"></i> Descargar Comprobante PDF
        </a>
    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>