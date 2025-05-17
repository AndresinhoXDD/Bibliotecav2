<?php
// ya arrancó sesión en index.php
if (
    empty($_SESSION['usuario'])
    || !in_array($_SESSION['usuario']['usuario_rol_id'], [1, 2])
) {
    header('Location: /BibliotecaV2/index.php?accion=login');
    exit;
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>catálogo de libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?accion=panel_bibliotecario">« volver</a>
            <div class="d-flex">
                <span class="navbar-text me-3">
                    hola, <?= htmlspecialchars($usuario['usuario_nombre']) ?> (bibliotecario)
                </span>
                <a class="btn btn-outline-secondary" href="index.php?accion=logout">cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>catálogo de libros</h1>
        <form class="row g-2 mb-3" method="get" action="/bibliotecav2/index.php">
            <input type="hidden" name="accion" value="catalogo_libros">
            <div class="col-auto">
                <input type="text" name="q" class="form-control" placeholder="buscar título, isbn o autor"
                    value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary">buscar</button>
            </div>
        </form>


        <table class="table table-hover">
            <thead>
                <tr>
                    <th>título</th>
                    <th>autor(es)</th>
                    <th>isbn</th>
                    <th>disponibles</th>
                    <th>totales</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($libros as $libro): ?>
                    <tr>
                        <td><?= htmlspecialchars($libro['libro_titulo']) ?></td>
                        <td><?= htmlspecialchars($libro['autor_nombre'] ?: '—') ?></td>
                        <td><?= htmlspecialchars($libro['libro_isbn']) ?></td>
                        <td><?= $libro['libro_copias_disponibles'] ?></td>
                        <td><?= $libro['libro_copias_totales'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>