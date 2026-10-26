<?php
require_once 'conexion.php';

$mensaje = "";
$tipo_alerta = "";

try {
    $pdo = obtenerConexion();

    // 1. Cargar las listas iniciales
    $regiones      = $pdo->query("SELECT * FROM region ORDER BY nombre_region ASC")->fetchAll(PDO::FETCH_ASSOC);
    $tipos_evento  = $pdo->query("SELECT * FROM tipo_evento ORDER BY nombre_tipo ASC")->fetchAll(PDO::FETCH_ASSOC);
    $organizadores = $pdo->query("SELECT * FROM organizador ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

    // 2. Procesar el formulario cuando se envía por POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $titulo          = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre'])) : '';
        $fecha           = isset($_POST['fecha']) ? htmlspecialchars(trim($_POST['fecha'])) : '';
        $hora_simple     = isset($_POST['hora']) ? htmlspecialchars(trim($_POST['hora'])) : '';
        $descripcion     = isset($_POST['descripcion']) ? htmlspecialchars(trim($_POST['descripcion'])) : '';
        $cant_invitados  = isset($_POST['cant_invitados']) && (int)$_POST['cant_invitados'] > 0 ? (int)$_POST['cant_invitados'] : 50;

        $id_region       = (int)($_POST['id_region'] ?? 0);
        $id_comuna       = (int)($_POST['id_comuna'] ?? 0);
        $id_recinto      = (int)($_POST['id_recinto'] ?? 0);
        $id_tipo_evento  = (int)($_POST['id_tipo_evento'] ?? 1);
        $id_organizador  = (int)($_POST['id_organizador'] ?? 1);

        if (!empty($titulo) && !empty($fecha) && !empty($hora_simple) && !empty($id_recinto) && !empty($id_comuna) && !empty($id_region)) {
            
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
            $mensaje = "Por favor, completa la región, comuna y recinto correspondientes.";
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

                        <div class="form-group">
                            <label for="cant_invitados">Cantidad de Invitados *</label>
                            <input type="number" id="cant_invitados" name="cant_invitados" min="1" max="1000" placeholder="Ej: 50" required>
                        </div>
                    </div>

                    <!-- PASO 1: REGIÓN -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_region">Seleccionar Región *</label>
                            <select id="id_region" name="id_region" required onchange="cargarComunas(this.value)">
                                <option value="">-- Selecciona Región --</option>
                                <?php foreach ($regiones as $reg): ?>
                                    <option value="<?php echo $reg['id_region']; ?>">
                                        <?php echo htmlspecialchars($reg['nombre_region']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- PASO 2: COMUNA -->
                        <div class="form-group">
                            <label for="id_comuna">Seleccionar Comuna *</label>
                            <select id="id_comuna" name="id_comuna" disabled required onchange="cargarRecintos(this.value)">
                                <option value="">-- Selecciona Región Primero --</option>
                            </select>
                        </div>
                    </div>

                    <!-- PASO 3: RECINTO -->
                    <div class="form-group">
                        <label for="id_recinto">Seleccionar Recinto / Lugar *</label>
                        <select id="id_recinto" name="id_recinto" disabled required>
                            <option value="">-- Selecciona Comuna Primero --</option>
                        </select>
                    </div>

                    <!-- CLASIFICADORES -->
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

    <!-- JavaScript para comunicación en la misma carpeta paginas/ -->
    <script>
    function cargarComunas(idRegion) {
        const comboComuna = document.getElementById('id_comuna');
        const comboRecinto = document.getElementById('id_recinto');

        comboComuna.innerHTML = '<option value="">Cargando comunas...</option>';
        comboComuna.disabled = true;

        comboRecinto.innerHTML = '<option value="">-- Selecciona Comuna Primero --</option>';
        comboRecinto.disabled = true;

        if (!idRegion) {
            comboComuna.innerHTML = '<option value="">-- Selecciona Región Primero --</option>';
            return;
        }

        fetch(`./obtener_ubicaciones.php?action=comunas&id_region=${idRegion}`)
            .then(res => res.json())
            .then(data => {
                comboComuna.innerHTML = '<option value="">-- Selecciona Comuna --</option>';
                data.forEach(c => {
                    comboComuna.innerHTML += `<option value="${c.id_comuna}">${c.nombre_comuna}</option>`;
                });
                comboComuna.disabled = false;
            })
            .catch(err => {
                console.error("Error al cargar comunas:", err);
                comboComuna.innerHTML = '<option value="">Error al cargar comunas</option>';
            });
    }

    function cargarRecintos(idComuna) {
        const comboRecinto = document.getElementById('id_recinto');

        comboRecinto.innerHTML = '<option value="">Cargando recintos...</option>';
        comboRecinto.disabled = true;

        if (!idComuna) {
            comboRecinto.innerHTML = '<option value="">-- Selecciona Comuna Primero --</option>';
            return;
        }

        fetch(`./obtener_ubicaciones.php?action=recintos&id_comuna=${idComuna}`)
            .then(res => res.json())
            .then(data => {
                comboRecinto.innerHTML = '<option value="">-- Selecciona Recinto --</option>';
                if (data.length === 0) {
                    comboRecinto.innerHTML = '<option value="">Sin recintos registrados en esta comuna</option>';
                    return;
                }
                data.forEach(r => {
                    comboRecinto.innerHTML += `<option value="${r.id_recinto}">${r.nombre_recinto} (Capacidad: ${r.capacidad_personas} personas)</option>`;
                });
                comboRecinto.disabled = false;
            })
            .catch(err => {
                console.error("Error al cargar recintos:", err);
                comboRecinto.innerHTML = '<option value="">Error al cargar recintos</option>';
            });
    }
    </script>

</body>
</html>