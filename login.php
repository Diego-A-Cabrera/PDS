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
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1c1e22; /* Fondo oscuro */
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #2c2f33;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #4a76a8;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: white;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            background-color: #23272a;
            color: white;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4a76a8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #3c5a7b;
        }

        .error-message {
            color: #D8000C;
            background-color: #FFBABA;
            border: 1px solid #D8000C;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #888;
        }

        .footer a {
            color: #4a76a8;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
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
