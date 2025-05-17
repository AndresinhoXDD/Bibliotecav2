<?php
require_once __DIR__ . '/conexion.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Conexion::obtener_conexion();
    }

    // método existente
    public function autenticar($email, $contrasena) {
        $sql = "SELECT usuario_id, usuario_nombre, usuario_email, usuario_rol_id
                FROM usuario
                WHERE usuario_email = ? AND usuario_contrasena = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss', $email, $contrasena);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $usuario ?: false;
    }

    /**
     * Lista usuarios cuyos roles estén en el array $roles
     * @param array $roles  IDs de rol a filtrar (p.ej [2,3])
     * @return array       Lista de usuarios con rol_nombre
     */
    public function listar_por_roles(array $roles) {
        // Convertir array a placeholders (?,?)
        $in  = str_repeat('?,', count($roles) - 1) . '?';
        $sql = "SELECT u.usuario_id, u.usuario_nombre, u.usuario_email, u.usuario_rol_id, r.rol_nombre
                FROM usuario u
                JOIN rol r ON u.usuario_rol_id = r.rol_id
                WHERE u.usuario_rol_id IN ($in)";
        $stmt = $this->db->prepare($sql);

        // bind_param dinámico
        $tipos = str_repeat('i', count($roles));
        $stmt->bind_param($tipos, ...$roles);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $usuarios;
    }

    /**
     * Actualiza el rol de un usuario
     * @param int $usuarioId
     * @param int $nuevoRol
     * @return bool  true si se actualizó, false si falla
     */
    public function actualizar_rol(int $usuarioId, int $nuevoRol) {
        $sql = "UPDATE usuario SET usuario_rol_id = ? WHERE usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $nuevoRol, $usuarioId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
?>
