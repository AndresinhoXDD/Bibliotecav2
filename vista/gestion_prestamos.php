<?php
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
    <title>gestión de préstamos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="/bibliotecav2/index.php?accion=panel_bibliotecario">« volver</a>
    <div class="d-flex">
      <span class="navbar-text me-3">
        hola, <?= htmlspecialchars($usuario['usuario_nombre']) ?> (bibliotecario)
      </span>
      <a class="btn btn-outline-secondary" href="/bibliotecav2/index.php?accion=logout">cerrar sesión</a>
    </div>
  </div>
</nav>

<<div class="container mt-4">
  <h1>gestión de préstamos</h1>
  
  <table class="table table-striped">
    <thead>
      <tr>
        <th>prestatario</th>
        <th>cédula</th>
        <th>fecha préstamo</th>
        <th>devolución prevista</th>
        <th>ejemplares</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($prestamos as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p['prestatario_nombre']) ?></td>
        <td><?= htmlspecialchars($p['prestatario_identificacion']) ?></td>
        <td><?= substr($p['prestamo_fecha_prestamo'],0,10) ?></td>
        <td><?= $p['fecha_devolucion_prevista'] ?></td>
        <td>
          <button class="btn btn-sm btn-info"
                  data-bs-toggle="modal"
                  data-bs-target="#modal-<?= $p['prestamo_id'] ?>">
            ver ejemplares
          </button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Aquí generamos los modales, **fuera** de la tabla -->
  <?php foreach ($prestamos as $p): ?>
  <div class="modal fade" id="modal-<?= $p['prestamo_id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">ejemplares prestados</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <table class="table">
            <thead>
              <tr>
                <th>título</th>
                <th>autor(es)</th>
                <th>isbn</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($p['ejemplares'] as $e): ?>
              <tr>
                <td><?= htmlspecialchars($e['libro_titulo']) ?></td>
                <td><?= htmlspecialchars($e['autor_nombre'] ?: '—') ?></td>
                <td><?= htmlspecialchars($e['libro_isbn']) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

</div>

<!-- Carga el bundle al final -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 