<?php
require_once 'conexion.php';

$mensaje = "";
$tipo_alerta = "";

try {
    $pdo = obtenerConexion();

    // 1. Obtener eventos activos
    $eventos = $pdo->query("SELECT id_evento, id_tipo_evento, descripcion, fecha_evento FROM evento ORDER BY fecha_evento DESC")->fetchAll(PDO::FETCH_ASSOC);

    // 2. Obtener lista de tipos de invitado
    $tipos_invitados = $pdo->query("SELECT * FROM tipo_invitado ORDER BY id_tipo_invitado ASC")->fetchAll(PDO::FETCH_ASSOC);

    // 3. Registrar una persona NUEVA si se envía el formulario rápido
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['accion_crear_persona'])) {
        $nombre_nuevo   = htmlspecialchars(trim($_POST['nombre_nuevo'] ?? ''));
        $apellido_nuevo = htmlspecialchars(trim($_POST['apellido_nuevo'] ?? ''));
        $correo_nuevo   = htmlspecialchars(trim($_POST['correo_nuevo'] ?? ''));
        $id_tipo_nuevo  = (int)($_POST['id_tipo_invitado_nuevo'] ?? 1);

        if (!empty($nombre_nuevo) && !empty($apellido_nuevo) && !empty($correo_nuevo)) {
            $id_invitado_nuevo = rand(100, 999);
            $sql_ins = "INSERT INTO invitado (id_invitado, nombre, apellido, correo, id_tipo_invitado) 
                        VALUES (:id, :nom, :ape, :cor, :tipo)";
            $stmt_ins = $pdo->prepare($sql_ins);
            $stmt_ins->execute([
                'id'   => $id_invitado_nuevo,
                'nom'  => $nombre_nuevo,
                'ape'  => $apellido_nuevo,
                'cor'  => $correo_nuevo,
                'tipo' => $id_tipo_nuevo
            ]);
            $mensaje = "¡Persona '{$nombre_nuevo} {$apellido_nuevo}' registrada con éxito en MariaDB!";
            $tipo_alerta = "alert-success";
        } else {
            $mensaje = "Completa todos los campos para registrar al nuevo invitado.";
            $tipo_alerta = "alert-error";
        }
    }

    // 4. Procesar la asignación MÚLTIPLE de personas al evento
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['accion_asignar_masivo'])) {
        $id_evento_raw = $_POST['id_evento'] ?? '';
        $personas_seleccionadas = $_POST['personas_ids'] ?? [];

        if (!empty($id_evento_raw) && !empty($personas_seleccionadas)) {
            list($id_evento, $id_tipo_evento) = explode('|', $id_evento_raw);

            $sql = "INSERT INTO invitado_has_evento (id_invitado, id_tipo_invitado, id_evento, id_tipo_evento) 
                    VALUES (:id_invitado, :id_tipo_invitado, :id_evento, :id_tipo_evento)
                    ON DUPLICATE KEY UPDATE id_invitado = VALUES(id_invitado)";

            $stmt = $pdo->prepare($sql);
            $total_asignados = 0;

            foreach ($personas_seleccionadas as $item) {
                list($id_invitado, $id_tipo_invitado) = explode('|', $item);
                $stmt->execute([
                    'id_invitado'      => $id_invitado,
                    'id_tipo_invitado' => $id_tipo_invitado,
                    'id_evento'        => $id_evento,
                    'id_tipo_evento'   => $id_tipo_evento
                ]);
                $total_asignados++;
            }

            $mensaje = "¡Se vincularon {$total_asignados} personas exitosamente al evento!";
            $tipo_alerta = "alert-success";
        } else {
            $mensaje = "Debes seleccionar un evento y al menos una persona de la lista.";
            $tipo_alerta = "alert-error";
        }
    }

    // 5. Cargar todos los invitados disponibles para la lista con checkboxes
    $invitados = $pdo->query("SELECT i.*, ti.nombre_tipo 
                              FROM invitado i 
                              INNER JOIN tipo_invitado ti ON i.id_tipo_invitado = ti.id_tipo_invitado 
                              ORDER BY ti.nombre_tipo ASC, i.nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

    // 6. Consultar y AGRUPAR las invitaciones por evento
    $sql_listado = "SELECT ih.id_evento, e.descripcion AS evento_nombre, e.fecha_evento, i.nombre, i.apellido, i.correo, ti.nombre_tipo
                    FROM invitado_has_evento ih
                    INNER JOIN evento e ON ih.id_evento = e.id_evento
                    INNER JOIN invitado i ON ih.id_invitado = i.id_invitado
                    INNER JOIN tipo_invitado ti ON i.id_tipo_invitado = ti.id_tipo_invitado
                    ORDER BY e.fecha_evento DESC, i.nombre ASC";
    $raw_listado = $pdo->query($sql_listado)->fetchAll(PDO::FETCH_ASSOC);

    $eventos_agrupados = [];
    foreach ($raw_listado as $item) {
        $id_evt = $item['id_evento'];
        if (!isset($eventos_agrupados[$id_evt])) {
            $eventos_agrupados[$id_evt] = [
                'nombre'       => $item['evento_nombre'],
                'fecha'        => $item['fecha_evento'],
                'participantes'=> []
            ];
        }
        $eventos_agrupados[$id_evt]['participantes'][] = $item;
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
    <title>Gestión de Invitaciones - CFTLA</title>
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
                    <li><a href="editar_evento.php"><i class="fa-solid fa-pen-to-square"></i> Editar Evento</a></li>
                </ul>
            </div>

            <div class="menu-section">
                <span class="menu-title">INVITACIONES</span>
                <ul>
                    <li>
                        <a href="gestionar_invitaciones.php"><i class="fa-solid fa-envelope-open-text"></i> Asignar Invitaciones</a>
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

        <main class="main-content">
            <div class="welcome-banner">
                <h1>Asignación Masiva de Invitaciones</h1>
                <p>Selecciona un evento, busca por nombre o correo y marca a las personas para vincularlas.</p>
            </div>

            <?php if (!empty($mensaje)): ?>
                <div class="alert <?php echo $tipo_alerta; ?>" style="padding: 12px; margin-bottom: 15px; border-radius: 6px; font-weight: bold;">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <!-- REGISTRO RÁPIDO -->
            <details style="margin-bottom: 20px; background: #fff; border-radius: 8px; padding: 10px; border: 1px solid #cbd5e1;">
                <summary style="font-weight: bold; color: #0284c7; cursor: pointer;">
                    <i class="fa-solid fa-user-plus"></i> ¿La persona que buscas no está en la lista? Haz clic aquí para registrarla
                </summary>
                <form action="gestionar_invitaciones.php" method="POST" style="margin-top: 15px; padding: 10px; background-color: #f8fafc; border-radius: 6px;">
                    <input type="hidden" name="accion_crear_persona" value="1">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nombre *</label>
                            <input type="text" name="nombre_nuevo" required placeholder="Ej: Carlos">
                        </div>
                        <div class="form-group">
                            <label>Apellido *</label>
                            <input type="text" name="apellido_nuevo" required placeholder="Ej: Mendoza">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Correo Electrónico *</label>
                            <input type="email" name="correo_nuevo" required placeholder="ejemplo@cftla.cl">
                        </div>
                        <div class="form-group">
                            <label>Tipo de Invitado *</label>
                            <select name="id_tipo_invitado_nuevo" required>
                                <?php foreach ($tipos_invitados as $t_inv): ?>
                                    <option value="<?php echo $t_inv['id_tipo_invitado']; ?>"><?php echo htmlspecialchars($t_inv['nombre_tipo']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit" style="background-color: #10b981; margin-top: 5px;">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar e Incluir en la Lista
                    </button>
                </form>
            </details>

            <!-- FORMULARIO DE ASIGNACIÓN -->
            <div class="card card-form" style="margin-bottom: 25px;">
                <div class="card-header">
                    <i class="fa-solid fa-users-rectangle"></i>
                    <h3>Seleccionar Evento y Marcar Lista de Invitados</h3>
                </div>

                <form action="gestionar_invitaciones.php" method="POST" class="form-container">
                    <input type="hidden" name="accion_asignar_masivo" value="1">
                    
                    <div class="form-group">
                        <label for="id_evento">1. Seleccionar Evento Destino *</label>
                        <select id="id_evento" name="id_evento" required>
                            <option value="">-- Selecciona el Evento --</option>
                            <?php foreach ($eventos as $evt): ?>
                                <option value="<?php echo $evt['id_evento'] . '|' . $evt['id_tipo_evento']; ?>">
                                    <?php echo htmlspecialchars($evt['descripcion']); ?> (Fecha: <?php echo $evt['fecha_evento']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <label style="font-weight: bold;"><i class="fa-solid fa-list-check"></i> 2. Marca a las personas que deseas invitar:</label>
                            <div>
                                <button type="button" onclick="marcarVisibles(true)" style="padding: 4px 8px; font-size: 0.8em; cursor: pointer;">Marcar Visibles</button>
                                <button type="button" onclick="marcarVisibles(false)" style="padding: 4px 8px; font-size: 0.8em; cursor: pointer;">Desmarcar Todos</button>
                            </div>
                        </div>

                        <!-- CAMPO DE BÚSQUEDA / FILTRO EN TIEMPO REAL -->
                        <div style="position: relative; margin-bottom: 10px;">
                            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 12px; color: #94a3b8;"></i>
                            <input type="text" id="filtro_invitado" onkeyup="filtrarListaInvitados()" placeholder="Buscar invitado por nombre, correo o tipo..." style="width: 100%; padding: 8px 12px 8px 35px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; font-size: 0.9em;">
                        </div>

                        <!-- LISTA FILTRABLE -->
                        <div id="contenedor_invitados" style="max-height: 250px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 6px; padding: 5px; background-color: #ffffff;">
                            <?php if (!empty($invitados)): ?>
                                <?php foreach ($invitados as $inv): ?>
                                    <label class="item-invitado" style="display: flex; align-items: center; justify-content: space-between; padding: 8px; border-bottom: 1px solid #f1f5f9; cursor: pointer;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <input type="checkbox" name="personas_ids[]" class="chk-persona" value="<?php echo $inv['id_invitado'] . '|' . $inv['id_tipo_invitado']; ?>" style="width: 18px; height: 18px;">
                                            <div>
                                                <strong class="texto-nombre"><?php echo htmlspecialchars($inv['nombre'] . ' ' . $inv['apellido']); ?></strong>
                                                <span class="texto-correo" style="color: #64748b; font-size: 0.85em; margin-left: 8px;"><?php echo htmlspecialchars($inv['correo']); ?></span>
                                            </div>
                                        </div>
                                        <span class="texto-tipo" style="background-color: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold;">
                                            <?php echo htmlspecialchars($inv['nombre_tipo']); ?>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="padding: 10px; text-align: center; color: #64748b;">No hay personas registradas en la base de datos.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" style="margin-top: 15px;">
                        <i class="fa-solid fa-paper-plane"></i> Vincular Personas Seleccionadas al Evento
                    </button>
                </form>
            </div>

            <!-- TABLA DESPLEGABLE POR EVENTO -->
            <div class="card card-form">
                <div class="card-header">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <h3>Lista de Invitaciones Asignadas (Desplegable por Evento)</h3>
                </div>

                <div style="padding: 15px;">
                    <?php if (!empty($eventos_agrupados)): ?>
                        <?php foreach ($eventos_agrupados as $id_evt => $info): ?>
                            <details style="margin-bottom: 12px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden;">
                                <summary style="padding: 12px 15px; font-weight: bold; background-color: #f1f5f9; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                                    <span>
                                        <i class="fa-solid fa-calendar-check" style="color: #0284c7; margin-right: 8px;"></i>
                                        Evento: <strong style="color: #0f172a;"><?php echo htmlspecialchars($info['nombre']); ?></strong>
                                        <small style="color: #64748b; margin-left: 10px;">(Fecha: <?php echo $info['fecha']; ?>)</small>
                                    </span>
                                    <span style="background-color: #0284c7; color: white; padding: 3px 10px; border-radius: 12px; font-size: 0.85em;">
                                        <?php echo count($info['participantes']); ?> Invitados
                                    </span>
                                </summary>

                                <div style="padding: 10px 15px; background-color: #ffffff;">
                                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9em;">
                                        <thead>
                                            <tr style="border-bottom: 2px solid #e2e8f0; color: #475569;">
                                                <th style="padding: 8px;">Nombre Completo</th>
                                                <th style="padding: 8px;">Correo Electrónico</th>
                                                <th style="padding: 8px;">Tipo de Invitado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($info['participantes'] as $p): ?>
                                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                                    <td style="padding: 8px; font-weight: 500; color: #1e293b;"><?php echo htmlspecialchars($p['nombre'] . ' ' . $p['apellido']); ?></td>
                                                    <td style="padding: 8px; color: #0284c7;"><?php echo htmlspecialchars($p['correo']); ?></td>
                                                    <td style="padding: 8px;">
                                                        <span style="background-color: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 4px; font-size: 0.85em; font-weight: bold;">
                                                            <?php echo htmlspecialchars($p['nombre_tipo']); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </details>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="padding: 15px; text-align: center; color: #64748b;">No hay asignaciones registradas en la tabla 'invitado_has_evento'.</p>
                    <?php endif; ?>
                </div>
            </div>

        </main>

    </div>

    <!-- JAVASCRIPT: FILTRO EN TIEMPO REAL Y BOTONES -->
    <script>
    function filtrarListaInvitados() {
        const input = document.getElementById('filtro_invitado');
        const filtro = input.value.toLowerCase();
        const items = document.querySelectorAll('.item-invitado');

        items.forEach(item => {
            const nombre = item.querySelector('.texto-nombre').textContent.toLowerCase();
            const correo = item.querySelector('.texto-correo').textContent.toLowerCase();
            const tipo   = item.querySelector('.texto-tipo').textContent.toLowerCase();

            if (nombre.includes(filtro) || correo.includes(filtro) || tipo.includes(filtro)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function marcarVisibles(estado) {
        const items = document.querySelectorAll('.item-invitado');
        items.forEach(item => {
            if (item.style.display !== 'none') {
                const chk = item.querySelector('.chk-persona');
                if (chk) chk.checked = estado;
            }
        });
    }
    </script>

</body>
</html>