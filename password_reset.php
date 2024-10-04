<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/password_reset.css">
</head>
<body>
    <div class="reset-container">
        <h2>Recuperar contraseña</h2>

        <!-- Mensajes de error o éxito -->
        <?php if (isset($error) && $error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (isset($success) && $success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="process_password_reset.php">
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" required>

            <label for="answer1">¿Cuál fue el nombre de la escuela a la que fuiste?</label>
            <input type="text" name="answer1" id="answer1" required>

            <label for="answer2">¿Cuál fue el nombre de tu primera mascota?</label>
            <input type="text" name="answer2" id="answer2" required>

            <label for="answer3">¿Cuál es tu película favorita?</label>
            <input type="text" name="answer3" id="answer3" required>

            <input type="submit" value="Enviar">
        </form>
    </div>
</body>
</html>
