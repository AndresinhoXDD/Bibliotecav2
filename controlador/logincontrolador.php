<?php
date_default_timezone_set('America/Bogota');

require_once __DIR__ . '/../modelo/usuario.php';

class LoginControlador
{
    private $modelo_usuario;

    public function __construct()
    {
        $this->modelo_usuario = new Usuario();
    }

    public function login()
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $contrasena = trim($_POST['contrasena'] ?? '');
            if ($email === '' || $contrasena === '') {
                $error = 'Ingrese email y contraseña.';
            } else {
                $usuario = $this->modelo_usuario->autenticar($email, $contrasena);
                if ($usuario) {
                    $_SESSION['usuario'] = $usuario;
                    // Redirigir según rol

                    if ($usuario['usuario_rol_id'] == 1) {
                        header('Location: vista/panel_administrador.php');
                    } else {
                        header('Location: vista/panel_bibliotecario.php');
                    }

                    exit;
                } else {
                    $error = 'Credenciales inválidas.';
                }
            }
        }
        require __DIR__ . '/../vista/login.php';
    }

    public function logout()
    {
        session_destroy();
        // Redirijo al front-controller en la raíz del proyecto
        header('Location: /BibliotecaV2/index.php');
        exit;
    }
}
