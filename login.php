<?php
include 'db.php'; // Incluye el archivo con la conexión a la base de datos
session_start();

$error = ''; // Variable para almacenar mensajes de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validar campos
    if (!empty($username) && !empty($password)) {
        try {
            // Preparar consulta para obtener datos del usuario
            $stmt = $pdo->prepare("SELECT id, username, password, role, is_active, failed_attempts, last_attempt FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si el usuario existe
            if ($user) {
                // Verificar si la cuenta está activa
                if ($user['is_active'] == 0) {
                    $error = "Tu cuenta ha sido bloqueada. Por favor, contacta al administrador.";
                } else {
                    // Verificar si la contraseña es correcta
                    if (password_verify($password, $user['password'])) {
                        // Reiniciar contador de intentos fallidos
                        $stmt = $pdo->prepare("UPDATE users SET failed_attempts = 0, last_attempt = NULL WHERE id = :id");
                        $stmt->execute(['id' => $user['id']]);

                        // Iniciar sesión y redirigir al usuario según su rol
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['role'] = $user['role'];

                        // Registrar en el log de inicio de sesión en la tabla user_logs
                        $logStmt = $pdo->prepare("INSERT INTO user_logs (user_id, action, timestamp) VALUES (:user_id, 'login', NOW())");
                        $logStmt->execute(['user_id' => $user['id']]);

                        // Redirigir al usuario según su rol
                        if ($user['role'] === 'administrador') {
                            header("Location: admin_dashboard.php");
                        } else {
                            header("Location: dashboard.php");
                        }
                        exit();
                    } else {
                        // Incrementar el contador de intentos fallidos
                        $failedAttempts = $user['failed_attempts'] + 1;
                        $lastAttempt = date('Y-m-d H:i:s');

                        if ($failedAttempts >= 3) {
                            // Bloquear la cuenta
                            $stmt = $pdo->prepare("UPDATE users SET is_active = 0, failed_attempts = :failed_attempts, last_attempt = :last_attempt WHERE id = :id");
                            $stmt->execute(['id' => $user['id'], 'failed_attempts' => $failedAttempts, 'last_attempt' => $lastAttempt]);

                            // Registrar el evento en el log
                            $logStmt = $pdo->prepare("INSERT INTO user_logs (user_id, action, timestamp) VALUES (:user_id, 'too many failed attempts', NOW())");
                            $logStmt->execute(['user_id' => $user['id']]);

                            $error = "Tu cuenta ha sido bloqueada debido a demasiados intentos fallidos.";
                        } else {
                            // Actualizar el contador de intentos fallidos
                            $stmt = $pdo->prepare("UPDATE users SET failed_attempts = :failed_attempts, last_attempt = :last_attempt WHERE id = :id");
                            $stmt->execute(['id' => $user['id'], 'failed_attempts' => $failedAttempts, 'last_attempt' => $lastAttempt]);

                            // Registrar el intento fallido en el log
                            $logStmt = $pdo->prepare("INSERT INTO user_logs (user_id, action, timestamp) VALUES (:user_id, 'failed login attempt', NOW())");
                            $logStmt->execute(['user_id' => $user['id']]);

                            $error = "Usuario y/o contraseña incorrectos.";
                        }
                    }
                }
            } else {
                $error = "Usuario y/o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            $error = "Error al iniciar sesión: " . $e->getMessage();
        }
    } else {
        $error = "Todos los campos son requeridos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>

        <!-- Mostrar un mensaje de error si es necesario -->
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="username">Nombre de usuario</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Iniciar Sesión">
        </form>

        <div class="footer">
            <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
            <p><a href="password_reset.php">¿Olvidaste tu contraseña?</a></p>
        </div>
    </div>
</body>

</html>