<?php
require_once 'conexion.php';

$eventos = [];

try {
    $pdo = obtenerConexion();
    
    // Consultar eventos uniendo con la tabla recinto para mostrar el lugar real
    $sql = "SELECT e.*, r.nombre_recinto 
            FROM evento e 
            LEFT JOIN recinto r ON e.id_recinto = r.id_recinto 
            ORDER BY e.fecha_evento ASC, e.hora ASC";
            
    $stmt = $pdo->query($sql);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Si ocurre un error de conexión o consulta
    $error = "Error al cargar eventos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - CFTLA</title>
    <link rel="stylesheet" href="../estilo/estilo_base.css">
    <link rel="stylesheet" href="../estilo/estiloIndex.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Barra Superior -->
    <header class="topbar">
        <div class="logo">
            <h2>CFT<span>LA</span></h2>
        </div>
        <div class="search-bar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Buscar evento...">
        </div>
    </header>

    <!-- Contenedor Principal -->
    <div class="dashboard-container">
        
        <!-- Menú Lateral -->
        <aside class="sidebar">
            <div class="menu-section">
                <span class="menu-title">EVENTOS</span>
                <ul>
                    <li class="active">
                        <a href="index.php"><i class="fa-solid fa-house"></i> Inicio</a>
                    </li>
                    <li>
                        <a href="crear_evento.php"><i class="fa-solid fa-calendar-plus"></i> Crear Evento</a>
                    </li>
                    <li>
                        <a href="resumen_evento.php"><i class="fa-solid fa-chart-pie"></i> Resumen Evento</a>
                    </li>
                    <li>
                        <a href="editar_evento.php"><i class="fa-solid fa-pen-to-square"></i> Editar Evento</a>
                    </li>
                </ul>
            </div>

            <div class="menu-section">
                <span class="menu-title">INVITACIONES</span>
                <ul>
                    <li>
                        <a href="configurar_invitaciones.php"><i class="fa-solid fa-envelope-open-text"></i> Configurar</a>
                    </li>
                    <li>
                        <a href="gmailmenu.php"><i class="fa-solid fa-paper-plane"></i> Enviar</a>
                    </li>
                    <li>
                        <a href="iniciar_sesion.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Área de Contenido Principal -->
        <main class="main-content">
            <div class="welcome-banner">
                <h1>¡Hola, Administrador!</h1>
                <p>Información detallada de los próximos eventos y actividades en CFTLA.</p>
            </div>

            <!-- Alerta de estado tras eliminar -->
            <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'eliminado'): ?>
                <div class="alert alert-success" style="padding: 12px; margin-bottom: 15px; background-color: #d4edda; color: #155724; border-radius: 6px; font-weight: bold;">
                    ¡El evento fue eliminado correctamente de MariaDB!
                </div>
            <?php endif; ?>

            <!-- Mostrar mensaje de error si ocurriera alguno -->
            <?php if (isset($error)): ?>
                <div class="alert alert-error" style="padding: 12px; margin-bottom: 15px; background-color: #f8d7da; color: #721c24; border-radius: 6px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Grid de Tarjetas de Eventos -->
            <div class="cards-grid" style="display: flex; flex-wrap: wrap; gap: 20px;">
                
                <?php if (!empty($eventos)): ?>
                    <?php foreach ($eventos as $evt): ?>
                        <?php 
                            // Formatear hora
                            $hora_formateada = date('h:i A', strtotime($evt['hora']));
                            $lugar_nombre = !empty($evt['nombre_recinto']) ? $evt['nombre_recinto'] : 'CFTLA Central';
                        ?>
                        <div class="card card-blue" style="flex: 1; min-width: 280px; max-width: 350px;">
                            <div class="card-header">
                                <i class="fa-solid fa-calendar-day"></i>
                                <h3><?php echo htmlspecialchars($evt['descripcion']); ?></h3>
                            </div>
                            <div class="card-body">
                                <p><i class="fa-solid fa-clock"></i> <strong>Hora:</strong> <?php echo $hora_formateada; ?></p>
                                <p><i class="fa-solid fa-calendar"></i> <strong>Fecha:</strong> <?php echo $evt['fecha_evento']; ?></p>
                                <p><i class="fa-solid fa-location-dot"></i> <strong>Lugar:</strong> <?php echo htmlspecialchars($lugar_nombre); ?></p>
                                <p><i class="fa-solid fa-users"></i> <strong>Invitados:</strong> <?php echo $evt['cant_invitados']; ?></p>
                                
                                <hr style="margin: 10px 0; border: 0; border-top: 1px dashed #ccc;">
                                <p style="font-size: 0.9em; color: #555;">
                                    Estado: <span style="font-weight: bold; color: green;"><?php echo ucfirst($evt['estado']); ?></span>
                                </p>
                                
                                <!-- Botones de Acción: Editar y Eliminar -->
                                <div class="card-actions" style="display: flex; gap: 10px; margin-top: 12px;">
                                    <!-- Botón Editar -->
                                    <a href="editar_evento.php?id=<?php echo $evt['id_evento']; ?>" 
                                       style="flex: 1; text-align: center; padding: 6px 10px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-size: 0.85em;">
                                        <i class="fa-solid fa-pen-to-square"></i> Editar
                                    </a>

                                    <!-- Botón Eliminar con alerta confirmación -->
                                    <a href="eliminar_evento.php?id=<?php echo $evt['id_evento']; ?>" 
                                       style="flex: 1; text-align: center; padding: 6px 10px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 4px; font-size: 0.85em;"
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar este evento?');">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </a>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay eventos registrados en la base de datos en este momento.</p>
                <?php endif; ?>

            </div>
        </main>

    </div>

</body>
</html>