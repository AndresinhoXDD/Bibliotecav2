<?php
// controlador/prestamocontrolador.php
require_once __DIR__ . '/../modelo/prestamo.php';

class prestamocontrolador {
    private $modelo_prestamo;

    public function __construct() {
        $this->modelo_prestamo = new Prestamo();
    }

    public function gestion_prestamos() {
        // sólo bibliotecarios
        if (
            empty($_SESSION['usuario']) ||
            $_SESSION['usuario']['usuario_rol_id'] != 2
        ) {
            header('Location: /bibliotecav2/index.php?accion=login');
            exit;
        }

        // 1) lista de préstamos
        $prestamos = $this->modelo_prestamo->listar_prestamos();

        // 2) para cada préstamo obtengo sus ejemplares
        foreach ($prestamos as &$p) {
            $p['ejemplares'] = $this->modelo_prestamo
                ->obtener_ejemplares_por_prestamo($p['prestamo_id']);
        }
        unset($p);

        // 3) cargo la vista
        require __DIR__ . '/../vista/gestion_prestamos.php';
    }
}
?>
