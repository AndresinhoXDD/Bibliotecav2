<?php
// controlador/librocontrolador.php
require_once __DIR__ . '/../modelo/libro.php';

class librocontrolador {
    private $modelo_libro;

    public function __construct() {
        $this->modelo_libro = new Libro();
    }

    public function catalogo() {
        // sólo bibliotecarios (2) y administradores (1)
        if (
            empty($_SESSION['usuario']) ||
            !in_array($_SESSION['usuario']['usuario_rol_id'], [1,2])
        ) {
            header('location: /bibliotecav2/index.php?accion=login');
            exit;
        }

        // leo el filtro de búsqueda (GET q)
        $filtro = trim($_GET['q'] ?? '');

        // paso el filtro al modelo
        $libros = $this->modelo_libro->listar_libros($filtro);

        // cargo la vista con $libros disponible
        require __DIR__ . '/../vista/catalogo_libros.php';
    }
}
?>
