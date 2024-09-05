<?php
include 'db.php'; // Incluye el archivo con la conexión a la base de datos
session_start(); // Inicia la sesión

$error = ''; // Variable para almacenar mensajes de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validar campos
    if (!empty($username) && !empty($password)) {
        try {
            // Preparar consulta para obtener datos del usuario
            $stmt = $pdo->prepare("SELECT id, username, password, role, is_active FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si el usuario existe y la contraseña es correcta
            if ($user && password_verify($password, $user['password'])) {
                // Verificar si la cuenta está activa
                if ($user['is_active'] == 0) {
                    $error = "Tu cuenta ha sido bloqueada. Por favor, contacta al administrador.";
                } else {
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
                }
            } else {
                $error = "Nombre de usuario o contraseña incorrectos.";
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
    <link rel="stylesheet" href="styles.css">
    <style>
        .error-message {
            color: #D8000C;
            background-color: #FFBABA;
            border: 1px solid #D8000C;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="input-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn-login">Iniciar sesión</button>
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
            <p><a href="password_reset.php">¿Olvidaste tu contraseña?</a></p> <!-- Enlace para recuperar contraseña -->
        </form>
    </div>
</body>

</html>