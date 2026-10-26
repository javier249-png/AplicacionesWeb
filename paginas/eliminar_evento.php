<?php
// Desactivar buffer previo para asegurar redirección limpia
ob_start();
require_once 'conexion.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_evento = (int)$_GET['id'];

    try {
        $pdo = obtenerConexion();

        $sql = "DELETE FROM evento WHERE id_evento = :id_evento";
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute(['id_evento' => $id_evento]);

        // Redireccionar a index.php notificando éxito
        header("Location: index.php?mensaje=eliminado");
        exit();

    } catch (PDOException $e) {
        // En caso de fallo de llave foránea u otro error SQL
        header("Location: index.php?mensaje=error");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
ob_end_flush();
?>