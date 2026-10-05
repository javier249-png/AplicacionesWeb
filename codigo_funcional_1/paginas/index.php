<?php
// Datos de la sesión y catálogo de eventos
$usuario_actual = "Administrador";

$eventos = [
    [
        "titulo" => "Bienvenida Novatos",
        "hora" => "10:00 AM",
        "lugar" => "Auditorio Principal",
        "descripcion" => "Jornada de recepción y orientación para los nuevos estudiantes del instituto.",
        "color_clase" => "card-blue",
        "icono" => "fa-calendar-day"
    ],
    [
        "titulo" => "Ceremonia de Titulación",
        "hora" => "18:30 PM",
        "lugar" => "Salón de Honor",
        "descripcion" => "Entrega de títulos a los egresados de las carreras técnicas.",
        "color_clase" => "card-orange",
        "icono" => "fa-award"
    ],
    [
        "titulo" => "Feria Técnico Profesional",
        "hora" => "11:30 AM",
        "lugar" => "Patio Central",
        "descripcion" => "Exposición de proyectos finales realizados por alumnos del CFT.",
        "color_clase" => "card-green",
        "icono" => "fa-chalkboard-user"
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Eventos CFTLA</title>
    
    <!-- 1. Estructura Global y Menú -->
    <link rel="stylesheet" href="../estilo/estilo_base.css">
    <!-- 2. Estilos Exclusivos de esta vista -->
    <link rel="stylesheet" href="../estilo/estiloIndex.css">
    
    <!-- Iconos -->
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

    <!-- Contenedor Flexbox -->
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

        <!-- Área de Contenido -->
        <main class="main-content">
            <div class="welcome-banner">
                <h1>¡Hola, <?php echo $usuario_actual; ?>!</h1>
                <p>Información detallada de los próximos eventos y actividades en CFTLA.</p>
            </div>

            <!-- Grilla Responsiva -->
            <div class="cards-grid">
                <?php foreach ($eventos as $evento): ?>
                    <div class="card <?php echo $evento['color_clase']; ?>">
                        <div class="card-header">
                            <i class="fa-solid <?php echo $evento['icono']; ?>"></i>
                            <h3><?php echo $evento['titulo']; ?></h3>
                        </div>
                        <div class="card-body">
                            <p class="card-info">
                                <i class="fa-solid fa-clock"></i> 
                                <strong>Hora:</strong> <?php echo $evento['hora']; ?>
                            </p>
                            <p class="card-info">
                                <i class="fa-solid fa-location-dot"></i> 
                                <strong>Lugar:</strong> <?php echo $evento['lugar']; ?>
                            </p>
                            <p class="card-desc"><?php echo $evento['descripcion']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>

    </div>

</body>
</html>