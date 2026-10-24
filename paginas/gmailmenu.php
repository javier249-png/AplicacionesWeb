<?php
// ==========================================================================
// BANCO DE DATOS SIMULADO (Simula el origen de los correos)
// ==========================================================================
$base_datos_correos = [
    "Todos los Estudiantes" => [
        "juan.perez@cftla.cl",
        "maria.gonzalez@cftla.cl",
        "carlos.rodriguez@cftla.cl",
        "ana.martinez@cftla.cl",
        "lucas.soto@cftla.cl"
    ],
    "Docentes y Directivos" => [
        "profe.silva@cftla.cl",
        "directora.lopez@cftla.cl",
        "coordinador.fuentes@cftla.cl"
    ],
    "Estudiantes de Informática" => [
        "juan.perez@cftla.cl",
        "lucas.soto@cftla.cl"
    ],
    "Egresados / Titulados" => [
        "exalumno.bravo@gmail.com",
        "titulado.tapia@gmail.com"
    ]
];

// Variables para mensajes de estado
$mensaje = "";
$tipo_alerta = "";
$correos_destinatarios = [];

// ==========================================================================
// PROCESAMIENTO DEL FORMULARIO (POST)
// ==========================================================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $destinatario = isset($_POST['destinatario']) ? htmlspecialchars(trim($_POST['destinatario'])) : '';
    $evento = isset($_POST['evento']) ? htmlspecialchars(trim($_POST['evento'])) : '';
    $asunto = isset($_POST['asunto']) ? htmlspecialchars(trim($_POST['asunto'])) : '';
    $cuerpo = isset($_POST['cuerpo']) ? htmlspecialchars(trim($_POST['cuerpo'])) : '';

    // Validar campos obligatorios
    if (!empty($destinatario) && !empty($asunto) && !empty($cuerpo)) {
        
        // Verificar si el grupo seleccionado existe en el arreglo
        if (array_key_exists($destinatario, $base_datos_correos)) {
            $correos_destinatarios = $base_datos_correos[$destinatario];
            $total_enviados = count($correos_destinatarios);

            $mensaje = "¡Invitaciones enviadas con éxito a <strong>{$total_enviados} destinatarios</strong> del grupo '<strong>{$destinatario}</strong>'!";
            $tipo_alerta = "alert-success";
        } else {
            $mensaje = "El grupo seleccionado no posee correos asignados.";
            $tipo_alerta = "alert-error";
        }

    } else {
        $mensaje = "Por favor, completa todos los campos marcados como obligatorios (*).";
        $tipo_alerta = "alert-error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Invitaciones - CFTLA</title>
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

    <!-- Contenedor Principal Flexbox -->
    <div class="dashboard-container">
        
        <!-- Menú Lateral -->
        <aside class="sidebar">
            <div class="menu-section">
                <span class="menu-title">EVENTOS</span>
                <ul>
                    <li>
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
                    <li class="active">
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
                <h1>Enviar Invitaciones</h1>
                <p>Redacta y despacha correos masivos o individuales para los eventos programados.</p>
            </div>

            <!-- Alerta de Notificación PHP -->
            <?php if (!empty($mensaje)): ?>
                <div class="alert <?php echo $tipo_alerta; ?>">
                    <p><?php echo $mensaje; ?></p>
                    
                    <!-- Previsualización de los correos que recibieron la información -->
                    <?php if (!empty($correos_destinatarios)): ?>
                        <div style="margin-top: 10px; font-size: 0.85rem; border-top: 1px dashed rgba(0,0,0,0.15); padding-top: 8px;">
                            <strong>Correos procesados:</strong>
                            <ul style="margin-left: 20px; margin-top: 4px;">
                                <?php foreach ($correos_destinatarios as $correo): ?>
                                    <li><?php echo $correo; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Tarjeta contenedora del Formulario de Envío -->
            <div class="card card-form">
                <div class="card-header">
                    <i class="fa-solid fa-paper-plane"></i>
                    <h3>Módulo de Envíos Masivos</h3>
                </div>

                <form action="gmailmenu.php" method="POST" class="form-container">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="destinatario">Grupo de Destinatarios *</label>
                            <select id="destinatario" name="destinatario" required>
                                <option value="">Selecciona un grupo...</option>
                                <option value="Todos los Estudiantes">Todos los Estudiantes</option>
                                <option value="Docentes y Directivos">Docentes y Directivos</option>
                                <option value="Estudiantes de Informática">Estudiantes de Informática</option>
                                <option value="Egresados / Titulados">Egresados / Titulados</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="evento">Evento Asociado</label>
                            <select id="evento" name="evento">
                                <option value="General">Sin evento específico (Comunicado)</option>
                                <option value="Bienvenida Novatos">Bienvenida Novatos</option>
                                <option value="Ceremonia de Titulación">Ceremonia de Titulación</option>
                                <option value="Feria Técnico Profesional">Feria Técnico Profesional</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="asunto">Asunto del Correo *</label>
                        <input type="text" id="asunto" name="asunto" placeholder="Ej: Invitación Oficial - Feria Técnico Profesional CFTLA" required>
                    </div>

                    <div class="form-group">
                        <label for="cuerpo">Mensaje de la Invitación *</label>
                        <textarea id="cuerpo" name="cuerpo" rows="6" placeholder="Estimada comunidad, los invitamos cordialmente a participar..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-paper-plane"></i> Enviar Invitaciones
                    </button>
                </form>
            </div>
        </main>

    </div>

</body>
</html>