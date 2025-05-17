<?php
require_once __DIR__ . '/conexion.php';

class Libro {
    private $db;

    public function __construct() {
        $this->db = Conexion::obtener_conexion();
    }

    /**
     * Obtiene el catálogo de libros con título, autor(es), ISBN,
     * copias totales y copias disponibles.
     * @return array  Lista de libros
     */
    public function listar_libros(string $filtro = '') {
        // si no hay filtro, ejecutamos la consulta completa
        if ($filtro === '') {
            $sql = "
                SELECT 
                    l.libro_id,
                    l.libro_titulo,
                    l.libro_isbn,
                    l.libro_copias_totales,
                    l.libro_copias_disponibles,
                    GROUP_CONCAT(a.autor_nombre SEPARATOR ', ') AS autor_nombre
                FROM libro l
                LEFT JOIN libroautor_libroautor la ON l.libro_id = la.libroautor_libro_id
                LEFT JOIN autor a ON la.autor_id = a.autor_id
                GROUP BY l.libro_id
                ORDER BY l.libro_titulo ASC
            ";
            $resultado = $this->db->query($sql);
            return $resultado->fetch_all(MYSQLI_ASSOC);
        }
    
        // si hay filtro, aplicamos WHERE con tres LIKE
        $f = "%{$filtro}%";
        $sql = "
            SELECT 
                l.libro_id,
                l.libro_titulo,
                l.libro_isbn,
                l.libro_copias_totales,
                l.libro_copias_disponibles,
                GROUP_CONCAT(a.autor_nombre SEPARATOR ', ') AS autor_nombre
            FROM libro l
            LEFT JOIN libroautor_libroautor la ON l.libro_id = la.libroautor_libro_id
            LEFT JOIN autor a ON la.autor_id = a.autor_id
            WHERE l.libro_titulo LIKE ? 
               OR l.libro_isbn   LIKE ? 
               OR a.autor_nombre LIKE ?
            GROUP BY l.libro_id
            ORDER BY l.libro_titulo ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sss', $f, $f, $f);
        $stmt->execute();
        $libros = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $libros;
    }
    
}
?>
