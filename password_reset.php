<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="login-container">
        <h2>Recuperar Contraseña</h2>
        <form method="POST" action="password_reset.php">
            <div class="input-group">
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" id="email" required>
            </div>
            <button type="submit" class="btn-login">Enviar enlace de recuperación</button>
            <p>¿Recuerdas tu contraseña? <a href="login.php">Inicia sesión aquí</a></p>
        </form>
    </div>
</body>

</html>