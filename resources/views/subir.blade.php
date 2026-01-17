<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Subir Documentos</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm col-md-6 mx-auto">
            <div class="card-body">
                <h3 class="mb-4">Subir Documentos</h3>
                
                <form action="/subir-archivo" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Selecciona tu archivo (PDF)</label>
                        <input type="file" name="documento" class="form-control" accept=".pdf" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Subir al Servidor</button>
                    <a href="/menu" class="btn btn-link w-100 mt-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>