<?php
function obtenerConexion() {
    static $conexion = null;

    if ($conexion !== null) {
        return $conexion;
    }

    $host = 'localhost';
    $db = 'bdeventos';
    $user = 'root';
    $password = '123456'; 
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $conexion = new PDO($dsn, $user, $password, $options);
        return $conexion;
    } catch (PDOException $e) {
        die("Error crítico de conexión: " . $e->getMessage());
    }
}
?>