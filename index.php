<?php
// inicio de sesión UNA sola vez
session_start();

// incluyo controladores
require_once 'controlador/logincontrolador.php';
require_once 'controlador/usuariocontrolador.php';
require_once 'controlador/librocontrolador.php';
require_once 'controlador/prestamocontrolador.php';

$accion = $_GET['accion'] ?? 'login';

switch ($accion) {
    case 'login':
        $ctrlLogin = new LoginControlador();
        $ctrlLogin->login();
        break;

    case 'logout':
        $ctrlLogin = new LoginControlador();
        $ctrlLogin->logout();
        break;

    case 'panel_administrador':
        // simplemente cargo la vista del panel de admin
        // la vista validará session y rol
        require 'vista/panel_administrador.php';
        break;

    case 'listar_usuarios':
        $ctrlUser = new Usuariocontrolador();
        $ctrlUser->listar_usuarios();
        break;

    case 'actualizar_roles':
        $ctrlUser = new Usuariocontrolador();
        $ctrlUser->actualizar_roles();
        break;

    case 'panel_bibliotecario':
        require 'vista/panel_bibliotecario.php';
        break;

    case 'catalogo_libros':
        $ctrlLibro = new LibroControlador();
        $ctrlLibro->catalogo();
        break;

    case 'gestion_prestamos':
        $ctrlPre = new prestamocontrolador();
        $ctrlPre->gestion_prestamos();
        break;


    default:
        header('Location: index.php?accion=login');
        exit;
}
