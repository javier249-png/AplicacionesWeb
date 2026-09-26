<?php
// Guardaremos aquí los mensajes de error o éxito para mostrarlos en el HTML
$mensaje = "";

// 1. Verificamos si el formulario se envió mediante el botón "Entrar" (Método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Capturamos lo que el usuario escribió usando el atributo 'name' de los inputs
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // 3. Simulación de validación (aquí compararías con una base de datos más adelante)
    if ($usuario === "admin" && $clave === "1234") {
        $mensaje = "<p class='mensaje-exito'>¡Inicio de sesión correcto! Bienvenido $usuario.</p>";
        // Aquí podrías redireccionar a otra página:
        // header("Location: panel.php");
    } else {
        $mensaje = "<p class='mensaje-error'>Usuario o contraseña incorrectos.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="iniciar_sesion.css">
</head>
<body>

    <div class="login-container">
        <div class="logo_cft">
            <img src="cft_logo/logo_cft_.jpg" alt="Logo">
        </div>

        <h2>Inicio de Sesión</h2>

        <!-- Imprimimos el mensaje de error/éxito si el usuario ya intentó enviar el formulario -->
        <?php echo $mensaje; ?>

        <!-- Agregamos method="POST" para enviar los datos de forma segura -->
        <form action="" method="POST">
            <div class="iniciar_usuario">
                <!-- Se agregó name="usuario" -->
                <input type="text" name="usuario" placeholder="Usuario / Login" required>
            </div>
            
            <div class="Contraseña">
                <!-- Se agregó name="clave" -->
                <input type="password" name="clave" placeholder="Contraseña" required>
            </div>

            <button type="submit">Entrar</button>
        </form>
        
        <div class="separador-o">
            <span>O</span>
        </div>
    </div>

</body>
</html>