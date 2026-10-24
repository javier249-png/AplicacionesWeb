<?php
// Incluimos el archivo de conexión
require_once 'conexion.php';

// Lógica PHP para procesar e insertar el evento en MariaDB
$mensaje = "";
$tipo_alerta = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Captura de datos respetando los atributos name="" de tu formulario HTML
    $titulo       = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre'])) : '';
    $fecha        = isset($_POST['fecha']) ? htmlspecialchars(trim($_POST['fecha'])) : '';
    $hora_simple  = isset($_POST['hora']) ? htmlspecialchars(trim($_POST['hora'])) : '';
    $lugar        = isset($_POST['lugar']) ? htmlspecialchars(trim($_POST['lugar'])) : '';
    $categoria    = isset($_POST['categoria']) ? htmlspecialchars(trim($_POST['categoria'])) : 'card-blue';
    $descripcion  = isset($_POST['descripcion']) ? htmlspecialchars(trim($_POST['descripcion'])) : '';

    // Validar campos obligatorios
    if (!empty($titulo) && !empty($fecha) && !empty($hora_simple) && !empty($lugar)) {
        
        try {
            $pdo = obtenerConexion();

            // 1. Asegurar registros maestros para evitar errores de llaves foráneas (FK)
            $pdo->exec("INSERT IGNORE INTO region (id_region, nombre_region) VALUES (1, 'Biobío');");
            $pdo->exec("INSERT IGNORE INTO comuna (id_comuna, nombre_comuna, id_region) VALUES (1, 'Lota', 1);");
            $pdo->exec("INSERT IGNORE INTO recinto (id_recinto, nombre_recinto, capacidad_personas, id_comuna, id_region) VALUES (1, 'Auditorio CFTLA', 200, 1, 1);");
            $pdo->exec("INSERT IGNORE INTO organizador (id_organizador, nombre, apellido, cargo) VALUES (1, 'Patricia', 'Albornoz', 'Docente');");
            $pdo->exec("INSERT IGNORE INTO tipo_evento (id_tipo_evento, nombre_tipo, descripcion_tipo) VALUES (1, 'Académico', 'Eventos e informaciones');");

            // Formatear la hora en formato DATETIME para MariaDB (YYYY-MM-DD HH:MM:SS)
            $datetime_hora = "{$fecha} {$hora_simple}:00";
            $id_evento_nuevo = rand(100, 999);

            // 2. Consulta preparada para la tabla evento
            $sql = "INSERT INTO evento (
                        id_evento, cant_invitados, estado, descripcion, 
                        fecha_evento, hora, id_tipo_evento, id_organizador, 
                        id_recinto, id_comuna, id_region
                    ) VALUES (
                        :id_evento, :cant_invitados, :estado, :descripcion, 
                        :fecha_evento, :hora, :id_tipo_evento, :id_organizador, 
                        :id_recinto, :id_comuna, :id_region
                    )";

            $stmt = $pdo->prepare($sql);
            $resultado = $stmt->execute([
                'id_evento'       => $id_evento_nuevo,
                'cant_invitados'  => '50',
                'estado'          => 'activo',
                'descripcion'     => $titulo . ($descripcion ? " - " . $descripcion : ""),
                'fecha_evento'    => $fecha,
                'hora'            => $datetime_hora,
                'id_tipo_evento'  => 1,
                'id_organizador'  => 1,
                'id_recinto'      => 1,
                'id_comuna'       => 1,
                'id_region'       => 1
            ]);

            if ($resultado) {
                $mensaje = "¡El evento '<strong>{$titulo}</strong>' (ID: {$id_evento_nuevo}) ha sido registrado con éxito en MariaDB!";
                $tipo_alerta = "alert-success";
            }
        } catch (PDOException $e) {
            $mensaje = "Error al guardar en la base de datos: " . $e->getMessage();
            $tipo_alerta = "alert-error";
        }

    } else {
        $mensaje = "Por favor, completa todos los campos requeridos.";
        $tipo_alerta = "alert-error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evento - CFTLA</title>
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
            <input type="text" placeholder="Buscar...">
        </div>
    </header>

    <!-- Contenedor Principal (Flexbox) -->
    <div class="dashboard-container">
        
        <!-- Menú Lateral -->
        <aside class="sidebar">
            <div class="menu-section">
                <span class="menu-title">EVENTOS</span>
                <ul>
                    <li>
                        <a href="index.php"><i class="fa-solid fa-house"></i> Inicio</a>
                    </li>
                    <li class="active">
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
                <h1>Crear Nuevo Evento</h1>
                <p>Ingresa los detalles para registrar un nuevo evento en el sistema.</p>
            </div>

            <!-- Alerta de Notificación PHP -->
            <?php if (!empty($mensaje)): ?>
                <div class="alert <?php echo $tipo_alerta; ?>" style="padding: 12px; margin-bottom: 15px; border-radius: 6px; font-weight: bold;">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <!-- Tarjeta contenedora del Formulario -->
            <div class="card card-form">
                <div class="card-header">
                    <i class="fa-solid fa-calendar-plus"></i>
                    <h3>Información del Evento</h3>
                </div>

                <form action="crear_evento.php" method="POST" class="form-container">
                    
                    <div class="form-group">
                        <label for="titulo">Nombre del Evento *</label>
                        <input type="text" id="titulo" name="nombre" placeholder="Ej: Feria Tecnológica 2026" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="fecha">Fecha *</label>
                            <input type="date" id="fecha" name="fecha" required>
                        </div>

                        <div class="form-group">
                            <label for="hora">Hora *</label>
                            <input type="time" id="hora" name="hora" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lugar">Lugar / Ubicación *</label>
                        <input type="text" id="lugar" name="lugar" placeholder="Ej: Auditorio Central, CFTLA" required>
                    </div>

                    <div class="form-group">
                        <label for="color">Categoría / Color de Tarjeta</label>
                        <select id="color" name="categoria">
                            <option value="card-blue">Azul (General)</option>
                            <option value="card-orange">Naranja (Ceremonias / Especial)</option>
                            <option value="card-green">Verde (Ferias / Talleres)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" placeholder="Escribe un breve resumen de las actividades..."></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Evento
                    </button>
                </form>
            </div>
        </main>

    </div>

</body>
</html>