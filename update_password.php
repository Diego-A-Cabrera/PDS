<?php
include 'db.php'; // Incluye la conexión a la base de datos
include 'toggleConfirmPasswordVisibility.php';
include 'togglePasswordVisibility.php';
session_start();

$error = '';
$success = '';

// Verifica si el correo electrónico está almacenado en la sesión
if (isset($_SESSION['reset_email'])) {
    $email = $_SESSION['reset_email']; // Obtener el email desde la sesión
} else {
    // Si no está presente, redirigir a otra página o mostrar un mensaje de error
    $error = "No se pudo obtener el correo electrónico para restablecer la contraseña.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($email)) {
    // Obtener datos del formulario
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($new_password) && !empty($confirm_password)) {
        if ($new_password !== $confirm_password) {
            $error = "Las contraseñas no coinciden.";
        } elseif (!validate_password($new_password)) { // Validar la seguridad de la nueva contraseña
            $error = "La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula, un número y un carácter especial.";
        } else {
            try {
                // Hash de la nueva contraseña
                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

                // Actualizar la contraseña en la base de datos
                $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
                $stmt->execute([':password' => $hashedPassword, ':email' => $email]);

                // Obtener el ID del usuario a través del correo electrónico
                $stmt_user = $pdo->prepare("SELECT id FROM users WHERE email = :email");
                $stmt_user->execute([':email' => $email]);
                $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $user_id = $user['id'];

                    // Registrar la acción de restablecimiento de contraseña en user_logs
                    $log_stmt = $pdo->prepare("INSERT INTO user_logs (user_id, action, timestamp) VALUES (:user_id, :action, NOW())");
                    $log_stmt->execute([
                        ':user_id' => $user_id,
                        ':action' => 'password_reset'
                    ]);

                    $success = "Contraseña actualizada exitosamente.";
                } else {
                    $error = "Usuario no encontrado.";
                }

            } catch (PDOException $e) {
                $error = "Error al actualizar la contraseña: " . $e->getMessage();
            }
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}

// Función para validar la robustez de la contraseña
function validate_password($password)
{
    return strlen($password) >= 8 &&
        preg_match('/[A-Z]/', $password) &&
        preg_match('/[a-z]/', $password) &&
        preg_match('/[0-9]/', $password) &&
        preg_match('/[\W_]/', $password);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Contraseña</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/update_password.css">
    <?php if ($success): ?>
        <meta http-equiv="refresh" content="6;url=login.php">
    <?php endif; ?>
</head>

<body>
    <div class="container">
        <h2>Actualizar Contraseña</h2>

        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <p>Serás redirigido a la página de inicio de sesión en 6 segundos.</p>
        <?php endif; ?>

        <form method="POST" action="update_password.php">
        
            <label for="new_password">Nueva Contraseña:</label>
            <div class="password-container">
                <input type="password" name="new_password" id="new_password" required>
                <span class="toggle-password" onclick="togglePasswordVisibility('new_password', this)">👁️</span>
            </div>

            <label for="confirm_password">Confirmar contraseña</label>
            <div class="password-container">
                <input type="password" name="confirm_password" id="confirm_password" required>
                <span class="toggle-password" onclick="togglePasswordVisibility('confirm_password', this)">👁️</span>
            </div>

            <input type="submit" value="Actualizar Contraseña">
        </form>
    </div>
</body>

</html>