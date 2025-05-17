<?php
// vista/nuevo_prestamo.php



// validar sesión y rol bibliotecario (2)
if (
    empty($_SESSION['usuario']) ||
    $_SESSION['usuario']['usuario_rol_id'] != 2
) {
    header('Location: index.php?accion=login');
    exit;
}

$mensajeExito = $_SESSION['mensaje_exito'] ?? '';
$mensajeError = $_SESSION['mensaje_error'] ?? '';
unset($_SESSION['mensaje_exito'], $_SESSION['mensaje_error']);

// variables definidas en el controlador:
// $q, $ejemplares, $fechaHoy, $fechaPrevista
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo Préstamo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-plus-circle me-2"></i>Nuevo Préstamo</h2>
            <a href="index.php?accion=gestion_prestamos" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver a Préstamos
            </a>
        </div>

        <div class="card">
            <div class="card-body">

                <?php if ($mensajeError): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($mensajeError) ?></div>
                <?php endif; ?>

                <?php if ($mensajeExito): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($mensajeExito) ?></div>
                <?php endif; ?>

                <!-- Formulario de búsqueda de ejemplares -->
                <form method="get" action="index.php" class="row g-2 mb-4">
                    <input type="hidden" name="accion" value="nuevo_prestamo">
                    <div class="col-auto flex-grow-1">
                        <input
                            type="text"
                            class="form-control"
                            name="q"
                            placeholder="Buscar por título, autor o ISBN..."
                            value="<?= htmlspecialchars($q ?? '') ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Buscar
                        </button>
                    </div>
                </form>



                <!-- Formulario de registro de préstamo -->
                <form method="post" action="index.php?accion=registrar_prestamo" id="form-prestamo">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre del Usuario *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="cedula" class="form-label">Cédula / Identificación *</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Seleccione hasta 3 ejemplares disponibles *</label>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:1%">#</th>
                                        <th>Título</th>
                                        <th>Autor</th>
                                        <th>ISBN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ejemplares as $i => $e): ?>
                                        <tr>
                                            <td class="text-center">
                                                <input
                                                    type="checkbox"
                                                    name="ejemplar[]"
                                                    value="<?= $e['ejemplar_id'] ?>">
                                            </td>
                                            <td><?= htmlspecialchars($e['libro_titulo']) ?></td>
                                            <td><?= htmlspecialchars($e['autor_nombre'] ?: '—') ?></td>
                                            <td><?= htmlspecialchars($e['libro_isbn']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Préstamo</label>
                            <input
                                type="date"
                                class="form-control"
                                name="fecha_prestamo"
                                value="<?= $fechaHoy ?>"
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Devolución Prevista</label>
                            <input
                                type="date"
                                class="form-control"
                                name="fecha_prevista"
                                value="<?= $fechaPrevista ?>"
                                readonly>
                            <div class="form-text">Se calcula automáticamente (3 días hábiles)</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?accion=gestion_prestamos" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Registrar Préstamo
                        </button>
                    </div>
                </form>

                <!-- Modal de Éxito -->
                <?php if ($mensajeExito): ?>
                    <div class="modal fade show" id="modal-exito" style="display:block; background:rgba(0,0,0,0.5);" tabindex="-1">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title">Préstamo Registrado</h5>
                                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <?= htmlspecialchars($mensajeExito) ?>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <a href="index.php?accion=gestion_prestamos" class="btn btn-primary">
                                        Ir a Préstamos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>