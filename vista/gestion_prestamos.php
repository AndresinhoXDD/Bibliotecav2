<?php
// sesión iniciada en index.php; sólo bibliotecarios (rol_id = 2)
if (
  empty($_SESSION['usuario']) ||
  $_SESSION['usuario']['usuario_rol_id'] != 2
) {
  header('Location: /bibliotecav2/index.php?accion=login');
  exit;
}

$usuario = $_SESSION['usuario'];
// $prestamos y $filterActivo vienen del controlador

$prestamos = $prestamos ?? [];
foreach ($prestamos as &$p) {
  $p['ejemplares'] = $p['ejemplares'] ?? [];
}
unset($p);

$filterActivo = $filterActivo ?? false;

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

  <div class="container mt-4">
    <h1>gestión de préstamos</h1>

    <!-- botón para alternar filtro mora / todos -->
    <?php if ($filterActivo): ?>
      <a href="/bibliotecav2/index.php?accion=gestion_prestamos" class="btn btn-secondary mb-3">
        mostrar todos
      </a>
    <?php else: ?>
      <a href="/bibliotecav2/index.php?accion=gestion_prestamos&filter=mora" class="btn btn-danger mb-3">
        mostrar solo en mora
      </a>
    <?php endif; ?>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>prestatario</th>
          <th>cédula</th>
          <th>fecha préstamo</th>
          <th>devolución prevista</th>
          <th>estado</th>
          <th>ejemplares</th>
          <th>acción</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($prestamos as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['prestatario_nombre']) ?></td>
            <td><?= htmlspecialchars($p['prestatario_identificacion']) ?></td>
            <td><?= substr($p['prestamo_fecha_prestamo'], 0, 10) ?></td>
            <td><?= $p['fecha_devolucion_prevista'] ?></td>
            <td>
              <span class="badge <?= $p['estado'] === 'en mora' ? 'bg-danger' : 'bg-success' ?>">
                <?= $p['estado'] ?>
              </span>
            </td>
            <td>
              <button class="btn btn-sm btn-info"
                data-bs-toggle="modal"
                data-bs-target="#modal-ejemplares-<?= $p['prestamo_id'] ?>">
                ver ejemplares
              </button>
            </td>
            <td>
              <button class="btn btn-sm btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#modal-dev-<?= $p['prestamo_id'] ?>">
                devolución
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Modales de ejemplares -->
    <?php foreach ($prestamos as $p): ?>
      <div class="modal fade" id="modal-ejemplares-<?= $p['prestamo_id'] ?>" tabindex="-1" aria-hidden="true">
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
              <button class="btn btn-secondary" data-bs-dismiss="modal">cerrar</button>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Modales de devolución -->
    <?php foreach ($prestamos as $p): ?>
      <div class="modal fade" id="modal-dev-<?= $p['prestamo_id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">confirmar devolución</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <p><strong>Fecha préstamo:</strong> <?= substr($p['prestamo_fecha_prestamo'], 0, 10) ?></p>
              <p><strong>Devolución prevista:</strong> <?= $p['fecha_devolucion_prevista'] ?></p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">cancelar</button>
              <a href="/bibliotecav2/index.php?accion=confirmar_devolucion&prestamo_id=<?= $p['prestamo_id'] ?>"
                class="btn btn-warning">
                confirmar devolución
              </a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Modal de éxito -->
    <?php if (!empty($_SESSION['mensaje_exito'])): ?>
      <div class="modal fade show" id="modal-exito" style="display:block;" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-body text-center">
              <?= htmlspecialchars($_SESSION['mensaje_exito']) ?>
            </div>
            <div class="modal-footer justify-content-center">
              <button class="btn btn-success" onclick="document.getElementById('modal-exito').remove();">
                cerrar
              </button>
            </div>
          </div>
        </div>
      </div>
    <?php unset($_SESSION['mensaje_exito']);
    endif; ?>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>