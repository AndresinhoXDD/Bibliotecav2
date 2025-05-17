<?php
require_once __DIR__ . '/../modelo/libro.php';

class LibroControlador {
    private $modelo_libro;

    public function __construct() {
        $this->modelo_libro = new Libro();
    }

    // muestra el catálogo de libros para el bibliotecario
    public function catalogo() {
        // sólo bibliotecarios (rol_id = 2) y administradores pueden ver
        if (empty($_SESSION['usuario']) 
            || !in_array($_SESSION['usuario']['usuario_rol_id'], [1,2])) {
            header('Location: /BibliotecaV2/index.php?accion=login');
            $filtro = trim($_GET['q'] ?? '');
            $libros = $this->modelo_libro->listar_libros($filtro);
            require __DIR__ . '/../vista/catalogo_libros.php';
            exit;
        }

        $libros = $this->modelo_libro->listar_libros();
        require __DIR__ . '/../vista/catalogo_libros.php';
    }
}
?>
