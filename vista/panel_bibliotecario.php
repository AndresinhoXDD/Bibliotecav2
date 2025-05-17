<?php

if (empty($_SESSION['usuario']) || $_SESSION['usuario']['usuario_rol_id'] != 2) {
    header('Location: ../index.php');
    exit;
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<?php
// sesión ya arrancada en index.php
if (empty($_SESSION['usuario']) || $_SESSION['usuario']['usuario_rol_id'] != 2) {
    header('Location: /BibliotecaV2/index.php?accion=login');
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
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php?accion=panel_bibliotecario">biblioteca</a>
    <div class="d-flex">
  
      <span class="navbar-text me-3">
        hola, <?= htmlspecialchars($usuario['usuario_nombre']) ?> (bibliotecario)
      </span>
      <a class="btn btn-outline-secondary me-2" href="index.php?accion=logout">cerrar sesión</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h1>panel de bibliotecario</h1>
    <p>aquí podrás ver el catálogo de libros disponibles.</p>
    <a class="btn btn-primary" href="/bibliotecav2/index.php?accion=catalogo_libros">
        ver catálogo de libros
    </a>
</div>
</body>
</html>

