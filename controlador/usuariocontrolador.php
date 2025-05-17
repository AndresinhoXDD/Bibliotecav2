<?php
date_default_timezone_set('America/Bogota');

require_once __DIR__ . '/../modelo/usuario.php';

class Usuariocontrolador {
    private $modelo_usuario;

    public function __construct() {
        $this->modelo_usuario = new Usuario();
    }

    // muestra la lista de usuarios con rol bibliotecario o invitado
    public function listar_usuarios() {
        // sólo administradores
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['usuario_rol_id'] != 1) {
            header('Location: ../index.php');
            exit;
        }

        // obtenemos usuarios con rol 2 y 3
        $roles = [2, 3];
        $usuarios = $this->modelo_usuario->listar_por_roles($roles);

        // cargamos la vista y le pasamos $usuarios
        require __DIR__ . '/../vista/gestionar_usuarios.php';
    }

    // procesa el cambio de rol de un usuario
    public function actualizar_roles() {
        // sólo administradores
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['usuario_rol_id'] != 1) {
            header('Location: /BibliotecaV2/index.php?accion=login');
            exit;
        }
    
        $cambios = $_POST['nuevo_rol'] ?? [];
        foreach ($cambios as $usuario_id => $rol_id) {
            $rol_id = intval($rol_id);
            $usuario_id = intval($usuario_id);
            if (in_array($rol_id, [2, 3])) {
                $this->modelo_usuario->actualizar_rol($usuario_id, $rol_id);
            }
        }
    
        // **aquí** la redirección correcta al front-controller
        header('Location: /BibliotecaV2/index.php?accion=listar_usuarios');
        exit;
    }
    
}
?>
