<?php
// controlador/prestamocontrolador.php
require_once __DIR__ . '/../modelo/prestamo.php';

class prestamocontrolador
{
    private $modelo_prestamo;

    public function __construct()
    {
        $this->modelo_prestamo = new Prestamo();
    }

    public function gestion_prestamos()
    {
        // Verificar rol bibliotecario
        if (
            empty($_SESSION['usuario']) ||
            $_SESSION['usuario']['usuario_rol_id'] != 2
        ) {
            header('Location: /bibliotecav2/index.php?accion=login');
            exit;
        }

        // 1) Traer préstamos pendientes
        $prestamos = $this->modelo_prestamo->listar_prestamos();

        // 2) Calcular estado
        $hoy = new DateTime('today');
        foreach ($prestamos as &$p) {
            $prevista = new DateTime($p['fecha_devolucion_prevista']);
            $p['estado'] = ($prevista < $hoy) ? 'en mora' : 'a tiempo';
        }
        unset($p);

        // 2.1) Inyectar siempre la clave 'ejemplares'
        foreach ($prestamos as &$p) {
            $p['ejemplares'] = $this->modelo_prestamo
                ->obtener_ejemplares_por_prestamo($p['prestamo_id']);
        }
        unset($p);

        // 3) Filtrado opcional
        $filter = $_GET['filter'] ?? '';
        $filterActivo = false;
        if ($filter === 'mora') {
            $filterActivo = true;
            $prestamos = array_filter($prestamos, fn($p) => $p['estado'] === 'en mora');
        }

        // 4) Cargar vista
        require __DIR__ . '/../vista/gestion_prestamos.php';
    }

    public function confirmar_devolucion()
    {
        // sólo bibliotecarios
        if (
            empty($_SESSION['usuario']) ||
            $_SESSION['usuario']['usuario_rol_id'] != 2
        ) {
            header('Location: /bibliotecav2/index.php?accion=login');
            exit;
        }

        $id = intval($_GET['prestamo_id'] ?? 0);
        if ($id > 0) {
            $ok = $this->modelo_prestamo->confirmar_devolucion($id);
            if ($ok) {
                $_SESSION['mensaje_exito'] = 'la devolución ha sido exitosa.';
            }
        }
        // vuelvo al listado
        header('Location: /bibliotecav2/index.php?accion=gestion_prestamos');
        exit;
    }
    public function nuevo_prestamo()
    {
        if (
            empty($_SESSION['usuario']) ||
            $_SESSION['usuario']['usuario_rol_id'] != 2
        ) {
            header('Location: /bibliotecav2/index.php?accion=login');
            exit;
        }
        // lectura opcional del campo de búsqueda q
        $q = trim($_GET['q'] ?? '');
        $libros = $this->modelo_prestamo->buscar_libros_disponibles($q);
        // calcular fechas
        $hoy = new DateTime('now');
        // fecha prevista +3 días hábiles
        $dias = 0;
        $fprev = clone $hoy;
        while ($dias < 3) {
            $fprev->modify('+1 day');
            if ((int)$fprev->format('N') < 6) $dias++;
        }
        $fechaHoy = $hoy->format('Y-m-d');
        $fechaPrevista = $fprev->format('Y-m-d');

        require __DIR__ . '/../vista/nuevo_prestamo.php';
    }

    // procesa el alta de préstamo
    public function registrar_prestamo()
    {
        // validar rol
        if (
            empty($_SESSION['usuario']) ||
            $_SESSION['usuario']['usuario_rol_id'] != 2
        ) {
            header('Location: /bibliotecav2/index.php?accion=login');
            exit;
        }
        $nombre = trim($_POST['nombre'] ?? '');
        $cedula = trim($_POST['cedula'] ?? '');
        $ejemplarIds = $_POST['libro'] ?? [];  // array de IDs

        // validaciones básicas
        if ($nombre === '' || $cedula === '' || count($ejemplarIds) < 1 || count($ejemplarIds) > 3) {
            $_SESSION['mensaje_error'] = 'Debe ingresar datos y seleccionar entre 1 y 3 ejemplares distintos.';
            header('Location: /bibliotecav2/index.php?accion=nuevo_prestamo');
            exit;
        }
        $libroIds    = $_POST['libro'] ?? [];
        if (count($libroIds) < 1 || count($libroIds) > 3) {
            $_SESSION['mensaje_error'] = 'Debe seleccionar entre 1 y 3 libros distintos.';
            header('Location: index.php?accion=nuevo_prestamo');
            exit;
        }

        // Traducir cada libro_id a un ejemplar_id único
        $ejemplarIds = [];
        foreach ($libroIds as $lid) {
            $eid = $this->modelo_prestamo->obtener_ejemplar_disponible((int)$lid);
            if ($eid !== null) {
                $ejemplarIds[] = $eid;
            }
        }

        if (count($ejemplarIds) !== count($libroIds)) {
            $_SESSION['mensaje_error'] = 'Algún libro ya no está disponible.';
            header('Location: index.php?accion=nuevo_prestamo');
            exit;
        }
        $prestId = $this->modelo_prestamo->obtener_o_crear_prestatario($nombre, $cedula);
        $bibId   = $_SESSION['usuario']['usuario_id'];
        $fechaPrevista = $_POST['fecha_prevista'] ?? '';

        $ok = $this->modelo_prestamo->registrar_prestamo($prestId, $bibId, $ejemplarIds, $fechaPrevista);

        if ($ok) {
            $_SESSION['mensaje_exito'] = 'préstamo registrado con éxito.';
        } else {
            $_SESSION['mensaje_error'] = 'error al registrar el préstamo.';
        }
        header('Location: /bibliotecav2/index.php?accion=nuevo_prestamo');
        exit;
    }
}
