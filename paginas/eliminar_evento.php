<?php
ob_start();
require_once 'conexion.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_evento = (int)$_GET['id'];

    try {
        $pdo = obtenerConexion();

        // Iniciar transacción SQL para garantizar integridad
        $pdo->beginTransaction();

        // 1. Eliminar ÚNICAMENTE la relación entre las personas y este evento en la tabla pivote
        // (Esto deja intactas las filas de la tabla 'invitado')
        $sql_pivot = "DELETE FROM invitado_has_evento WHERE id_evento = :id_evento";
        $stmt_pivot = $pdo->prepare($sql_pivot);
        $stmt_pivot->execute(['id_evento' => $id_evento]);

        // 2. Eliminar el registro del evento en la tabla principal
        $sql_evento = "DELETE FROM evento WHERE id_evento = :id_evento";
        $stmt_evento = $pdo->prepare($sql_evento);
        $stmt_evento->execute(['id_evento' => $id_evento]);

        // Confirmar la transacción
        $pdo->commit();

        // Redireccionar a index.php notificando éxito
        header("Location: index.php?mensaje=eliminado");
        exit();

    } catch (PDOException $e) {
        // En caso de cualquier fallo, revertir todos los cambios realizados
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        header("Location: index.php?mensaje=error");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
ob_end_flush();
?>