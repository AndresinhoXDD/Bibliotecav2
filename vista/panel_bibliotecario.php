<?php
// vista/panel_bibliotecario.php
// sesión iniciada en index.php; solo rol bibliotecario (2)
if (
    empty($_SESSION['usuario']) ||
    $_SESSION['usuario']['usuario_rol_id'] != 2
) {
    header('Location: /bibliotecav2/index.php?accion=login');
    exit;
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>panel bibliotecario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-check me-2"></i>Panel bibliotecario</h2>
        <div>
            <span class="me-3">hola, <?= htmlspecialchars($usuario['usuario_nombre']) ?></span>
            <a href="/bibliotecav2/index.php?accion=logout" class="btn btn-outline-secondary">
                <i class="fas fa-sign-out-alt me-1"></i>cerrar sesión
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <a href="/bibliotecav2/index.php?accion=catalogo_libros" class="btn btn-primary w-100">
                <i class="fas fa-book me-1"></i>ver catálogo de libros
            </a>
        </div>
        <div class="col-md-3">
            <a href="/bibliotecav2/index.php?accion=gestion_prestamos" class="btn btn-success w-100">
                <i class="fas fa-tasks me-1"></i>gestión de préstamos
            </a>
        </div>
        <div class="col-md-3">
            <a href="/bibliotecav2/index.php?accion=nuevo_prestamo" class="btn btn-warning w-100">
                <i class="fas fa-plus-circle me-1"></i>nuevo préstamo
            </a>
        </div>
        <div class="col-md-3">
            <a href="index.php?accion=gestion_prestamos&filter=mora" class="btn btn-danger w-100">
                <i class="fas fa-exclamation-triangle me-1"></i>préstamos en mora
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
