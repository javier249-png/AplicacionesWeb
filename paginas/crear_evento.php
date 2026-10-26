<?php
require_once 'conexion.php';

$mensaje = "";
$tipo_alerta = "";

try {
    $pdo = obtenerConexion();

    // Consultar todas las opciones disponibles en MariaDB
    $tipos_evento  = $pdo->query("SELECT * FROM tipo_evento ORDER BY id_tipo_evento ASC")->fetchAll(PDO::FETCH_ASSOC);
    $organizadores = $pdo->query("SELECT * FROM organizador ORDER BY id_organizador ASC")->fetchAll(PDO::FETCH_ASSOC);
    $recintos      = $pdo->query("SELECT * FROM recinto ORDER BY id_recinto ASC")->fetchAll(PDO::FETCH_ASSOC);
    $comunas       = $pdo->query("SELECT * FROM comuna ORDER BY id_comuna ASC")->fetchAll(PDO::FETCH_ASSOC);
    $regiones      = $pdo->query("SELECT * FROM region ORDER BY id_region ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Procesar el formulario cuando el usuario guarda un evento
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $titulo          = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre'])) : '';
        $fecha           = isset($_POST['fecha']) ? htmlspecialchars(trim($_POST['fecha'])) : '';
        $hora_simple     = isset($_POST['hora']) ? htmlspecialchars(trim($_POST['hora'])) : '';
        $lugar           = isset($_POST['lugar']) ? htmlspecialchars(trim($_POST['lugar'])) : '';
        $descripcion     = isset($_POST['descripcion']) ? htmlspecialchars(trim($_POST['descripcion'])) : '';
        
        // Capturar la cantidad de invitados ingresada por el usuario
        $cant_invitados  = isset($_POST['cant_invitados']) && (int)$_POST['cant_invitados'] > 0 ? (int)$_POST['cant_invitados'] : 50;

        // Capturar las IDs elegidas por el usuario
        $id_tipo_evento  = (int)($_POST['id_tipo_evento'] ?? 1);
        $id_organizador  = (int)($_POST['id_organizador'] ?? 1);
        $id_recinto      = (int)($_POST['id_recinto'] ?? 1);
        $id_comuna       = (int)($_POST['id_comuna'] ?? 1);
        $id_region       = (int)($_POST['id_region'] ?? 1);

        if (!empty($titulo) && !empty($fecha) && !empty($hora_simple)) {
            $datetime_hora = "{$fecha} {$hora_simple}:00";
            $id_evento_nuevo = rand(100, 999);

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
                'cant_invitados'  => $cant_invitados,
                'estado'          => 'activo',
                'descripcion'     => $titulo . ($descripcion ? " - " . $descripcion : ""),
                'fecha_evento'    => $fecha,
                'hora'            => $datetime_hora,
                'id_tipo_evento'  => $id_tipo_evento,
                'id_organizador'  => $id_organizador,
                'id_recinto'      => $id_recinto,
                'id_comuna'       => $id_comuna,
                'id_region'       => $id_region
            ]);

            if ($resultado) {
                $mensaje = "¡El evento '<strong>{$titulo}</strong>' (ID: {$id_evento_nuevo}) ha sido registrado con éxito en MariaDB!";
                $tipo_alerta = "alert-success";
            }
        } else {
            $mensaje = "Por favor, completa todos los campos requeridos.";
            $tipo_alerta = "alert-error";
        }
    }
} catch (PDOException $e) {
    $mensaje = "Error en la base de datos: " . $e->getMessage();
    $tipo_alerta = "alert-error";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Evento - CFTLA</title>
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
                    <li class="active"><a href="crear_evento.php"><i class="fa-solid fa-calendar-plus"></i> Crear Evento</a></li>
                    <li><a href="resumen_evento.php"><i class="fa-solid fa-chart-pie"></i> Resumen Evento</a></li>
                    <li><a href="editar_evento.php"><i class="fa-solid fa-pen-to-square"></i> Editar Evento</a></li>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            <div class="welcome-banner">
                <h1>Crear Nuevo Evento</h1>
                <p>Ingresa los detalles para registrar un nuevo evento en el sistema.</p>
            </div>

            <?php if (!empty($mensaje)): ?>
                <div class="alert <?php echo $tipo_alerta; ?>" style="padding: 12px; margin-bottom: 15px; border-radius: 6px; font-weight: bold;">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

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

                        <!-- Nuevo campo: Cantidad de Invitados -->
                        <div class="form-group">
                            <label for="cant_invitados">Cantidad de Invitados *</label>
                            <input type="number" id="cant_invitados" name="cant_invitados" min="1" max="1000" placeholder="Ej: 50" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lugar">Lugar / Ubicación *</label>
                        <input type="text" id="lugar" name="lugar" placeholder="Ej: Auditorio Central, CFTLA" required>
                    </div>

                    <!-- Desplegables dinámicos desde MariaDB -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_tipo_evento">Tipo de Evento</label>
                            <select id="id_tipo_evento" name="id_tipo_evento">
                                <?php foreach ($tipos_evento as $tipo): ?>
                                    <option value="<?php echo $tipo['id_tipo_evento']; ?>">
                                        <?php echo htmlspecialchars($tipo['nombre_tipo']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_organizador">Organizador</label>
                            <select id="id_organizador" name="id_organizador">
                                <?php foreach ($organizadores as $org): ?>
                                    <option value="<?php echo $org['id_organizador']; ?>">
                                        <?php echo htmlspecialchars($org['nombre'] . ' ' . $org['apellido']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_recinto">Recinto</label>
                            <select id="id_recinto" name="id_recinto">
                                <?php foreach ($recintos as $rec): ?>
                                    <option value="<?php echo $rec['id_recinto']; ?>">
                                        <?php echo htmlspecialchars($rec['nombre_recinto']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_comuna">Comuna</label>
                            <select id="id_comuna" name="id_comuna">
                                <?php foreach ($comunas as $com): ?>
                                    <option value="<?php echo $com['id_comuna']; ?>">
                                        <?php echo htmlspecialchars($com['nombre_comuna']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_region">Región</label>
                            <select id="id_region" name="id_region">
                                <?php foreach ($regiones as $reg): ?>
                                    <option value="<?php echo $reg['id_region']; ?>">
                                        <?php echo htmlspecialchars($reg['nombre_region']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
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