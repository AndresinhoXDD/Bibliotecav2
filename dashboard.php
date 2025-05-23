<?php
session_start();
if (empty($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>panel biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">biblioteca</a>
    <div class="d-flex">
      <span class="navbar-text me-3">
        hola, <?= htmlspecialchars($usuario['usuario_nombre']) ?>
      </span>
      <a class="btn btn-outline-secondary" href="index.php?accion=logout">cerrar sesión</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h1>bienvenido al panel de administración</h1>
    <p>desde aquí podrás gestionar los libros, autores, préstamos, etc.</p>
</div>
</body>
</html>
