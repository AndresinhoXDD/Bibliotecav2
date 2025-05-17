<?php
require_once __DIR__ . '/conexion.php';

class Prestamo
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::obtener_conexion();
    }

    /**
     * Devuelve un array de préstamos con:
     * - prestatario_nombre
     * - prestatario_identificacion
     * - prestamo_fecha_prestamo
     * - fecha_devolucion_prevista (3 días hábiles después)
     *
     * @return array
     */
    public function listar_prestamos(): array
    {
        // 1) Traer datos básicos
        $sql = "
            SELECT 
                p.prestamo_id,
                pr.prestatario_nombre,
                pr.prestatario_identificacion,
                p.prestamo_fecha_prestamo
            FROM prestamo p
            JOIN prestatario pr ON p.prestamo_prestatario_id = pr.prestatario_id
            ORDER BY p.prestamo_fecha_prestamo DESC
        ";
        $resultado = $this->db->query($sql);
        $filas = $resultado->fetch_all(MYSQLI_ASSOC);

        // 2) Calcular fecha de devolución prevista (+3 días hábiles)
        foreach ($filas as &$fila) {
            $fecha = new DateTime($fila['prestamo_fecha_prestamo']);
            $diasHabiles = 0;
            // añadir 3 días hábiles
            while ($diasHabiles < 3) {
                $fecha->modify('+1 day');
                $diaSemana = (int)$fecha->format('N'); // 6=sábado, 7=domingo
                if ($diaSemana < 6) {
                    $diasHabiles++;
                }
            }
            $fila['fecha_devolucion_prevista'] = $fecha->format('Y-m-d');
        }
        unset($fila);

        return $filas;
    }
    public function obtener_ejemplares_por_prestamo(int $prestamoId): array
    {
        $sql = "
            SELECT 
                l.libro_titulo,
                l.libro_isbn,
                GROUP_CONCAT(a.autor_nombre SEPARATOR ', ') AS autor_nombre
            FROM prestamoejemplar pe
            JOIN ejemplar e ON pe.prestamoejemplar_ejemplar_id = e.ejemplar_id
            JOIN libro l     ON e.ejemplar_libro_id = l.libro_id
            LEFT JOIN libroautor_libroautor la ON l.libro_id = la.libroautor_libro_id
            LEFT JOIN autor a ON la.autor_id = a.autor_id
            WHERE pe.prestamoejemplar_prestamo_id = ?
            GROUP BY l.libro_id, l.libro_titulo, l.libro_isbn
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $prestamoId);
        $stmt->execute();
        $datos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $datos;
    }
}
