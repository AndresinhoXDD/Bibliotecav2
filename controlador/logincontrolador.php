<?php
date_default_timezone_set('america/bogota');

require_once __DIR__ . '/../modelo/usuario.php';

class logincontrolador
{
    private $modelo_usuario;

    public function __construct()
    {
        $this->modelo_usuario = new usuario();
    }

    public function login()
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $contrasena = trim($_POST['contrasena'] ?? '');
            if ($email === '' || $contrasena === '') {
                $error = 'ingrese email y contraseña.';
            } else {
                $usuario = $this->modelo_usuario->autenticar($email, $contrasena);
                if ($usuario) {
                    $_SESSION['usuario'] = $usuario;
                    // redirigir según rol
                    if ($usuario['usuario_rol_id'] == 1) {
                        header('location: /bibliotecav2/index.php?accion=panel_administrador');
                    } else {
                        header('location: /bibliotecav2/index.php?accion=panel_bibliotecario');
                    }
                    exit;
                } else {
                    $error = 'credenciales inválidas.';
                }
            }
        }
        require __DIR__ . '/../vista/login.php';
    }

    public function logout()
    {
        session_destroy();
        // redirigir al login
        header('location: /bibliotecav2/index.php?accion=login');
        exit;
    }
}
?>
