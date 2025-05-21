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
        // 1) Traer datos básicos SOLO de préstamos NO devueltos
        $sql = "
        SELECT 
            p.prestamo_id,
            pr.prestatario_nombre,
            pr.prestatario_identificacion,
            p.prestamo_fecha_prestamo
        FROM prestamo p
        JOIN prestatario pr 
          ON p.prestamo_prestatario_id = pr.prestatario_id
        WHERE p.prestamo_fecha_devolucion_real IS NULL
        ORDER BY p.prestamo_fecha_prestamo DESC
    ";
        $resultado = $this->db->query($sql);
        $filas = $resultado->fetch_all(MYSQLI_ASSOC);

        // 2) El resto (cálculo de fecha prevista) permanece igual...
        foreach ($filas as &$fila) {
            $fecha = new DateTime($fila['prestamo_fecha_prestamo']);
            $diasHabiles = 0;
            while ($diasHabiles < 3) {
                $fecha->modify('+1 day');
                $diaSemana = (int)$fecha->format('N');
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
            -- forzar DISTINCT en autores
            GROUP_CONCAT(DISTINCT a.autor_nombre SEPARATOR ', ') AS autor_nombre
        FROM prestamoejemplar pe
        JOIN ejemplar e ON pe.prestamoejemplar_ejemplar_id = e.ejemplar_id
        JOIN libro l     ON e.ejemplar_libro_id = l.libro_id
        LEFT JOIN libroautor_libroautor la 
            ON la.libroautor_libro_id = l.libro_id
        LEFT JOIN autor a 
            ON a.autor_id = la.autor_id
        WHERE pe.prestamoejemplar_prestamo_id = ?
        GROUP BY l.libro_id, l.libro_titulo, l.libro_isbn
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $prestamoId);
        $stmt->execute();
        $datos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $datos ?: [];
    }


    public function confirmar_devolucion(int $prestamoId): bool
    {
        // 1) marco fecha real de devolución
        $sql1 = "UPDATE prestamo 
                 SET prestamo_fecha_devolucion_real = NOW() 
                 WHERE prestamo_id = ?";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->bind_param('i', $prestamoId);
        $ok1 = $stmt1->execute();
        $stmt1->close();

        // 2) obtengo los ejemplares de ese préstamo
        $sql2 = "SELECT prestamoejemplar_ejemplar_id 
                 FROM prestamoejemplar 
                 WHERE prestamoejemplar_prestamo_id = ?";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param('i', $prestamoId);
        $stmt2->execute();
        $res   = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt2->close();

        // 3) libero cada ejemplar (estado = 'disponible')
        $sql3 = "UPDATE ejemplar 
                 SET ejemplar_estado = 'disponible' 
                 WHERE ejemplar_id = ?";
        $stmt3 = $this->db->prepare($sql3);
        foreach ($res as $fila) {
            $stmt3->bind_param('i', $fila['prestamoejemplar_ejemplar_id']);
            $stmt3->execute();
        }
        $stmt3->close();

        return $ok1;
    }
    public function buscar_ejemplares(string $filtro = ''): array
    {
        $f = "%{$filtro}%";
        $sql = "
        SELECT 
          e.ejemplar_id,
          l.libro_titulo,
          l.libro_isbn,
          GROUP_CONCAT(DISTINCT a.autor_nombre SEPARATOR ', ') AS autor_nombre
        FROM ejemplar e
        JOIN libro l ON e.ejemplar_libro_id = l.libro_id
        LEFT JOIN libroautor_libroautor la 
          ON la.libroautor_libro_id = l.libro_id
        LEFT JOIN autor a 
          ON a.autor_id = la.autor_id
        WHERE e.ejemplar_estado = 'disponible'
          AND (
               l.libro_titulo LIKE ?
            OR l.libro_isbn   LIKE ?
            OR a.autor_nombre LIKE ?
          )
        GROUP BY e.ejemplar_id
        ORDER BY l.libro_titulo ASC
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sss', $f, $f, $f);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $res;
    }

    public function buscar_libros_disponibles(string $filtro = ''): array
{
    $f = "%{$filtro}%";
    $sql = "
    SELECT 
      l.libro_id,
      l.libro_titulo,
      l.libro_isbn,
      GROUP_CONCAT(DISTINCT a.autor_nombre SEPARATOR ', ') AS autor_nombre,
      COUNT(e.ejemplar_id) AS disponibles
    FROM ejemplar e
    JOIN libro l ON e.ejemplar_libro_id = l.libro_id
    LEFT JOIN libroautor_libroautor la 
      ON la.libroautor_libro_id = l.libro_id
    LEFT JOIN autor a 
      ON a.autor_id = la.autor_id
    WHERE e.ejemplar_estado = 'disponible'
      AND (
           l.libro_titulo LIKE ?
        OR l.libro_isbn   LIKE ?
        OR a.autor_nombre LIKE ?
      )
    GROUP BY l.libro_id, l.libro_titulo, l.libro_isbn
    ORDER BY l.libro_titulo ASC
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('sss', $f, $f, $f);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $res;
}

public function obtener_ejemplar_disponible(int $libroId): ?int
{
    $sql = "
    SELECT ejemplar_id
    FROM ejemplar
    WHERE ejemplar_libro_id = ? AND ejemplar_estado = 'disponible'
    LIMIT 1
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('i', $libroId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $res ? (int)$res['ejemplar_id'] : null;
}


    /**
     * Crea o retorna prestatario existente por cédula
     */
    public function obtener_o_crear_prestatario(string $nombre, string $cedula): int
    {
        // buscar
        $sql1 = "SELECT prestatario_id FROM prestatario WHERE prestatario_identificacion = ?";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->bind_param('s', $cedula);
        $stmt1->execute();
        $res1 = $stmt1->get_result()->fetch_assoc();
        $stmt1->close();
        if ($res1) {
            return intval($res1['prestatario_id']);
        }
        // insertar
        $sql2 = "INSERT INTO prestatario (prestatario_nombre, prestatario_identificacion) VALUES (?,?)";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param('ss', $nombre, $cedula);
        $stmt2->execute();
        $nuevoId = $stmt2->insert_id;
        $stmt2->close();
        return $nuevoId;
    }

    /**
     * Registra un nuevo préstamo con hasta 3 ejemplares
     */
    public function registrar_prestamo(int $prestatarioId, int $bibliotecarioId, array $ejemplarIds, string $fechaPrevista): bool
    {
        // inicio transacción
        $this->db->begin_transaction();
        try {
            // insertar prestamo
            $sql1 = "INSERT INTO prestamo (prestamo_prestatario_id, prestamo_bibliotecario_id, prestamo_fecha_devolucion_prevista)
                     VALUES (?,?,?)";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->bind_param('iis', $prestatarioId, $bibliotecarioId, $fechaPrevista);
            $stmt1->execute();
            $prestamoId = $stmt1->insert_id;
            $stmt1->close();

            // insertar prestamoejemplar y actualizar estado
            $sql2 = "INSERT INTO prestamoejemplar (prestamoejemplar_prestamo_id, prestamoejemplar_ejemplar_id)
                     VALUES (?,?)";
            $stmt2 = $this->db->prepare($sql2);
            $sql3 = "UPDATE ejemplar SET ejemplar_estado='prestado' WHERE ejemplar_id = ?";
            $stmt3 = $this->db->prepare($sql3);

            foreach ($ejemplarIds as $eid) {
                $stmt2->bind_param('ii', $prestamoId, $eid);
                $stmt2->execute();
                $stmt3->bind_param('i', $eid);
                $stmt3->execute();
            }
            $stmt2->close();
            $stmt3->close();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
}
