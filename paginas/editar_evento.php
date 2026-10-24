<?php
require_once 'conexion.php';

$mensaje = "";
$tipo_alerta = "";

// 1. Obtener el ID del evento que viene en la URL (ej: editar_evento.php?id=778)
$id_evento = $_GET['id'] ?? $_POST['id_evento'] ?? null;

$nombre      = "";
$fecha       = "";
$hora        = "";
$descripcion = "";

try {
    $pdo = obtenerConexion();

    // 2. SI EL USUARIO PRESIONA "GUARDAR CAMBIOS" (POST)
    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($id_evento)) {
        $nombre      = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre'])) : '';
        $fecha       = isset($_POST['fecha']) ? htmlspecialchars(trim($_POST['fecha'])) : '';
        $hora_simple = isset($_POST['hora']) ? htmlspecialchars(trim($_POST['hora'])) : '';
        $descripcion = isset($_POST['descripcion']) ? htmlspecialchars(trim($_POST['descripcion'])) : '';

        $datetime_hora = "{$fecha} {$hora_simple}:00";
        $descripcion_completa = $nombre . ($descripcion ? " - " . $descripcion : "");

        // Actualizamos en MariaDB con UPDATE
        $sql = "UPDATE evento SET 
                    descripcion = :descripcion, 
                    fecha_evento = :fecha_evento, 
                    hora = :hora
                WHERE id_evento = :id_evento";

        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute([
            'descripcion'  => $descripcion_completa,
            'fecha_evento' => $fecha,
            'hora'         => $datetime_hora,
            'id_evento'    => $id_evento
        ]);

        if ($resultado) {
            $mensaje = "¡El evento (ID: {$id_evento}) fue actualizado con éxito en MariaDB!";
            $tipo_alerta = "alert-success";
        }
    }

    // 3. CARGAR LOS DATOS ACTUALES DEL EVENTO PARA MOSTRARLOS EN EL FORMULARIO
    if (!empty($id_evento)) {
        $sql_select = "SELECT * FROM evento WHERE id_evento = :id_evento";
        $stmt_select = $pdo->prepare($sql_select);
        $stmt_select->execute(['id_evento' => $id_evento]);
        $evento = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($evento) {
            $nombre = $evento['descripcion'];
            $fecha  = $evento['fecha_evento'];
            $hora   = date('H:i', strtotime($evento['hora']));
        }
    }

} catch (PDOException $e) {
    $mensaje = "Error: " . $e->getMessage();
    $tipo_alerta = "alert-error";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento - CFTLA</title>
    <link rel="stylesheet" href="../estilo/estilo_base.css">
    <link rel="stylesheet" href="../estilo/estiloIndex.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header class="topbar">
        <div class="logo"><h2>CFT<span>LA</span></h2></div>
    </header>

    <div class="dashboard-container">
        
        <aside class="sidebar">
            <div class="menu-section">
                <span class="menu-title">EVENTOS</span>
                <ul>
                    <li><a href="index.php"><i class="fa-solid fa-house"></i> Inicio</a></li>
                    <li><a href="crear_evento.php"><i class="fa-solid fa-calendar-plus"></i> Crear Evento</a></li>
                    <li><a href="resumen_evento.php"><i class="fa-solid fa-chart-pie"></i> Resumen Evento</a></li>
                    <li class="active"><a href="editar_evento.php"><i class="fa-solid fa-pen-to-square"></i> Editar Evento</a></li>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            <div class="welcome-banner">
                <h1>Editar Evento</h1>
                <p>Modifica los detalles para actualizar el evento en el sistema.</p>
            </div>

            <?php if (!empty($mensaje)): ?>
                <div class="alert <?php echo $tipo_alerta; ?>" style="padding: 12px; margin-bottom: 15px; border-radius: 6px; font-weight: bold;">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <div class="card card-form">
                <div class="card-header">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <h3>Información del Evento (ID: <?php echo htmlspecialchars($id_evento ?? 'Sin seleccionar'); ?>)</h3>
                </div>

                <form action="editar_evento.php" method="POST" class="form-container">
                    
                    <input type="hidden" name="id_evento" value="<?php echo htmlspecialchars($id_evento ?? ''); ?>">

                    <div class="form-group">
                        <label for="titulo">Nombre del Evento *</label>
                        <input type="text" id="titulo" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="fecha">Fecha *</label>
                            <input type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="hora">Hora *</label>
                            <input type="time" id="hora" name="hora" value="<?php echo htmlspecialchars($hora); ?>" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                    </button>
                </form>
            </div>
        </main>

    </div>

</body>
</html>