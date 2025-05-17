<?php
session_start();
if (empty($_SESSION['usuario']) || $_SESSION['usuario']['usuario_rol_id'] != 1) {
    header('Location: ../index.php');
    exit;
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>gestionar usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="panel_administrador.php">« volver</a>
    <span class="navbar-text">hola, <?= htmlspecialchars($usuario['usuario_nombre']) ?> (administrador)</span>
  </div>
</nav>

<div class="container mt-4">
    <h1>gestionar usuarios</h1>
    <form method="post" action="../controlador/usuarioControlador.php?accion=actualizar_roles">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>nombre</th>
                    <th>email</th>
                    <th>rol actual</th>
                    <th>cambiar a</th>
                    <th>acción</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se iterará con PHP desde el controlador -->
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['usuario_nombre']) ?></td>
                    <td><?= htmlspecialchars($u['usuario_email']) ?></td>
                    <td><?= $u['rol_nombre'] ?></td>
                    <td>
                        <select name="nuevo_rol[<?= $u['usuario_id'] ?>]" class="form-select">
                            <option value="2" <?= $u['usuario_rol_id']==2 ? 'selected' : '' ?>>bibliotecario</option>
                            <option value="3" <?= $u['usuario_rol_id']==3 ? 'selected' : '' ?>>invitado</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit" name="guardar" value="<?= $u['usuario_id'] ?>" class="btn btn-sm btn-success">
                            guardar
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>
</body>
</html>
