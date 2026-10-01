<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento - CFTLA</title>
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
                    <li>
                        <a href="crear_evento.php"><i class="fa-solid fa-calendar-plus"></i> Crear Evento</a>
                    </li>
                    <li>
                        <a href="resumen_evento.php"><i class="fa-solid fa-chart-pie"></i> Resumen Evento</a>
                    </li>
                    <li class="active">
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
                <h1>Editar Evento</h1>
                <p>Modifica los detalles para actualizar el evento en el sistema.</p>
            </div>

            <!-- Tarjeta contenedora del Formulario de Edición -->
            <div class="card card-form">
                <div class="card-header">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <h3>Información del Evento</h3>
                </div>

                <!-- Envía los cambios guardados de vuelta a resumen_evento.php -->
                <form action="resumen_evento.php" method="POST" class="form-container">
                    
                    <div class="form-group">
                        <label for="titulo">Nombre del Evento *</label>
                        <input type="text" id="titulo" name="nombre" value="<?php echo htmlspecialchars($nombre ?? ''); ?>" placeholder="Ej: Feria Tecnológica 2026" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="fecha">Fecha *</label>
                            <input type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars($fecha ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="hora">Hora *</label>
                            <input type="time" id="hora" name="hora" value="<?php echo htmlspecialchars($hora ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lugar">Lugar / Ubicación *</label>
                        <input type="text" id="lugar" name="lugar" value="<?php echo htmlspecialchars($lugar ?? ''); ?>" placeholder="Ej: Auditorio Central, CFTLA" required>
                    </div>

                    <div class="form-group">
                        <label for="color">Categoría / Color de Tarjeta</label>
                        <select id="color" name="categoria">
                            <option value="Azul (General)" <?php echo (isset($categoria) && $categoria === 'Azul (General)') ? 'selected' : ''; ?>>Azul (General)</option>
                            <option value="Naranja (Ceremonias)" <?php echo (isset($categoria) && $categoria === 'Naranja (Ceremonias)') ? 'selected' : ''; ?>>Naranja (Ceremonias / Especial)</option>
                            <option value="Verde (Ferias)" <?php echo (isset($categoria) && $categoria === 'Verde (Ferias)') ? 'selected' : ''; ?>>Verde (Ferias / Talleres)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" placeholder="Escribe un breve resumen de las actividades..."><?php echo htmlspecialchars($descripcion ?? ''); ?></textarea>
                    </div>

                    <div style="display: flex; gap: 15px; margin-top: 15px;">
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                        </button>
                        <a href="resumen_evento.php" class="btn-submit" style="background-color: #64748b; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: auto; padding: 10px 20px;">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </main>

    </div>

</body>
</html>