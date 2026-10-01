<?php
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Validación de credenciales
    if ($usuario === "admin" && $clave === "1234") {
        
        // Redirige al archivo del formulario
        header("Location: gmailmenu.php");
        exit(); // Detiene la ejecución del código para procesar el redireccionamiento inmediatamente

    } else {
        $mensaje = "<p class='mensaje-error' style='color: red;'>Usuario o contraseña incorrectos.</p>";
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

        <?php echo $mensaje; ?>

        <form action="" method="POST">
            <div class="iniciar_usuario">
                <input type="text" name="usuario" placeholder="Usuario / Login" required>
            </div>
            
            <div class="Contraseña">
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