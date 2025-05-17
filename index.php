<?php
require_once 'controlador/logincontrolador.php';
$controller = new LoginControlador();
$action = $_GET['action'] ?? 'login';

if ($action === 'logout') {
    $controller->logout();
} else {
    $controller->login();
}
?>
