<?php
require_once __DIR__ . '/auth.php';
iniciarSesionSegura();

if (!empty($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $clave = $_POST['clave'] ?? '';
    $usuarioValido = getenv('APP_LOGIN_USER') ?: 'admin';
    $claveValida = getenv('APP_LOGIN_PASSWORD') ?: '1234';

    if (hash_equals($usuarioValido, $usuario) && hash_equals($claveValida, $clave)) {
        session_regenerate_id(true);
        $_SESSION['usuario'] = $usuario;
        header('Location: index.php');
        exit;
    }

    $mensaje = 'Usuario o contraseña incorrectos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="../estilo/iniciar_sesion.css">
</head>
<body>

    <div class="login-container">
        <div class="logo_cft">
            <img src="cft_logo/logo_cft_.jpg" alt="Logo">
        </div>

        <h2>Inicio de Sesión</h2>

        <?php if ($mensaje !== ''): ?>
            <p class="mensaje-error" style="color: red;"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="iniciar_usuario">
                <input type="text" name="usuario" placeholder="Usuario / Login" autocomplete="username" required>
            </div>
            
            <div class="Contraseña">
                <input type="password" name="clave" placeholder="Contraseña" autocomplete="current-password" required>
            </div>

            <button type="submit">Entrar</button>
        </form>
        
        <div class="separador-o">
            <span>O</span>
        </div>
    </div>

</body>
</html>