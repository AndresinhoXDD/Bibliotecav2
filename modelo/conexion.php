<?php
class Conexion {
    private static $instancia = null;

    public static function obtener_conexion() {
        if (self::$instancia === null) {
            $host = 'localhost';
            $usuario = 'root';
            $contrasena = '';
            $bd = 'biblioteca';

            self::$instancia = new mysqli($host, $usuario, $contrasena, $bd);
            if (self::$instancia->connect_errno) {
                die('Error de conexión: ' . self::$instancia->connect_error);
            }
            self::$instancia->set_charset('utf8');
        }
        return self::$instancia;
    }

    // Evitar instanciación directa
    private function __construct() {}
    // Evitar clonación de la instancia
    private function __clone() {}
    // Permitir wakeup como público según requisitos de PHP
    public function __wakeup() {}
}
?>
