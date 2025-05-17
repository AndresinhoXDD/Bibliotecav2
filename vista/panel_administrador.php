<?php

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
  <title>panel administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">biblioteca</a>
      <div class="d-flex">
        <span class="navbar-text me-3">
          hola, <?= htmlspecialchars($usuario['usuario_nombre']) ?> (administrador)
        </span>
       <a class="btn btn-outline-secondary me-2" href="index.php?accion=logout">cerrar sesión</a>
 
        <!-- nuevo botón gestionar usuarios -->

      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <h1>panel de administrador</h1>
    <p>aquí podrás gestionar usuarios, roles y configuraciones generales.</p>
     <!-- invoca al método listar_usuarios() -->
     <a class="btn btn-success" href="/BibliotecaV2/index.php?accion=listar_usuarios">
    gestionar usuarios
 </a>

  </div>
</body>

</html>