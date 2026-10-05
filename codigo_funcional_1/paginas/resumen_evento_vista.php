<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen del Evento - CFTLA</title>
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
                    <li class="active">
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
                <h1>Resumen del Evento</h1>
                <p>Revisa la información registrada en el sistema.</p>
            </div>

            <!-- Tarjeta contenedora -->
            <div class="card card-form">
                <div class="card-header">
                    <i class="fa-solid fa-calendar-check"></i>
                    <h3>Información del Evento</h3>
                </div>

                <div class="form-container">
                    
                    <div class="form-group">
                        <label>Nombre del Evento *</label>
                        <p style="padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-top: 5px; font-weight: 500;">
                            <?php echo !empty($nombre) ? htmlspecialchars($nombre) : 'No especificado'; ?>
                        </p>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Fecha *</label>
                            <p style="padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-top: 5px;">
                                <?php echo !empty($fecha) ? htmlspecialchars($fecha) : 'No especificada'; ?>
                            </p>
                        </div>

                        <div class="form-group">
                            <label>Hora *</label>
                            <p style="padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-top: 5px;">
                                <?php echo !empty($hora) ? htmlspecialchars($hora) : 'No especificada'; ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Lugar / Ubicación *</label>
                        <p style="padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-top: 5px;">
                            <?php echo !empty($lugar) ? htmlspecialchars($lugar) : 'No especificado'; ?>
                        </p>
                    </div>

                    <div class="form-group">
                        <label>Categoría / Color de Tarjeta</label>
                        <p style="padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-top: 5px;">
                            <?php echo !empty($categoria) ? htmlspecialchars($categoria) : 'Azul (General)'; ?>
                        </p>
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <p style="padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-top: 5px; min-height: 60px;">
                            <?php echo !empty($descripcion) ? htmlspecialchars($descripcion) : 'Sin descripción'; ?>
                        </p>
                    </div>

                    <!-- Botones de Acción centrados y alineados -->
                    <div style="display: flex; gap: 15px; margin-top: 25px;">
                        
                        <form action="editar_evento.php" method="POST" style="margin: 0;">
                            <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
                            <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
                            <input type="hidden" name="hora" value="<?php echo htmlspecialchars($hora); ?>">
                            <input type="hidden" name="lugar" value="<?php echo htmlspecialchars($lugar); ?>">
                            <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($categoria); ?>">
                            <input type="hidden" name="descripcion" value="<?php echo htmlspecialchars($descripcion); ?>">
                            
                            <button type="submit" class="btn-submit" style="background-color: #0284c7; width: auto; padding: 10px 20px;">
                                <i class="fa-solid fa-pen-to-square"></i> Editar Evento
                            </button>
                        </form>

                        <a href="index.php" class="btn-submit" style="background-color: #64748b; text-decoration: none; width: auto; padding: 10px 20px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-house" style="margin-right: 8px;"></i> Volver al Inicio
                        </a>
                    </div>

                </div>
            </div>
        </main>

    </div>

</body>
</html>