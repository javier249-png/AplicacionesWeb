<?php
// Variables para almacenar y procesar los datos
$enviado = false;
$nombre = "";
$correo = "";
$mensaje_texto = "";

// 1. Comprobamos si los datos fueron enviados mediante el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Capturamos los datos del formulario usando los 'name'
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $mensaje_texto = $_POST['mensaje'] ?? '';

    // Marcamos como enviado para desplegar el resumen
    $enviado = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Invitaciones</title>
    <link rel="stylesheet" href="gmailmenu.css">
</head>
<body>

    <h1>Enviar invitaciones</h1>

    <!-- 3. Muestra el resumen de los datos procesados por PHP tras enviar (Exigido en pauta EV2) -->
    <?php if ($enviado): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h3>¡Invitación enviada exitosamente!</h3>
            <p><strong>Estudiante:</strong> <?php echo $nombre; ?></p>
            <p><strong>Correo:</strong> <?php echo $correo; ?></p>
            <p><strong>Mensaje:</strong> <?php echo nl2br($mensaje_texto); ?></p>
        </div>
    <?php endif; ?>

    <!-- 4. Formulario configurado con POST -->
    <form action="" method="POST">
        <div>
            <label for="nombre">Nombre del estudiante</label>
            <br>
            <!-- Se agregó name="nombre" y precarga en value -->
            <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
        </div>

        <div>
            <label for="correo">Correo electrónico</label>
            <br>
            <!-- Se agregó name="correo" y precarga en value -->
            <input type="email" id="correo" name="correo" value="<?php echo $correo; ?>" required>
        </div>

        <div>
            <label for="mensaje">Mensaje</label>
            <br>
            <!-- Se agregó name="mensaje" y precarga dentro del textarea -->
            <textarea id="mensaje" name="mensaje" rows="4" required><?php echo $mensaje_texto; ?></textarea>
        </div>

        <br>
        <button type="submit">Enviar Invitación</button>
    </form>

</body>
</html>