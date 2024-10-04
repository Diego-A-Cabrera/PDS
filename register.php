<?php
include 'db.php'; // Incluye la conexión a la base de datos
include 'toggleConfirmPasswordVisibility.php';
include 'togglePasswordVisibility.php';
session_start(); // Inicia la sesión

$error = ''; // Variable para almacenar mensajes de error
$success = ''; // Variable para almacenar el mensaje de éxito

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $security_question_1 = trim($_POST['security_question_1']);
    $security_question_2 = trim($_POST['security_question_2']);
    $security_question_3 = trim($_POST['security_question_3']);

    // Validar campos
    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "El formato del correo electrónico es inválido.";
        } elseif ($password !== $confirm_password) {
            $error = "Las contraseñas no coinciden.";
        } elseif (!validate_password($password)) { // Validar que la contraseña cumpla con los requisitos de seguridad
            $error = "La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula, un número y un carácter especial.";
        } else {
            // Verificar si el nombre de usuario o el correo electrónico ya existen
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $username, 'email' => $email]);
            $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                $error = "El nombre de usuario o el correo electrónico ya están en uso.";
            } else {
                try {
                    // Hash de la contraseña
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $security_question_1 = password_hash($security_question_1, PASSWORD_DEFAULT);
                    $security_question_2 = password_hash($security_question_2, PASSWORD_DEFAULT);
                    $security_question_3 = password_hash($security_question_3, PASSWORD_DEFAULT);

                    // Insertar nuevo usuario en la base de datos con marcadores de posición correctos
                    $stmt = $pdo->prepare("INSERT INTO users 
                        (username, email, password, role, is_active, security_question_1, security_question_2, security_question_3) 
                        VALUES (:username, :email, :password, :role, :is_active, :security_question_1, :security_question_2, :security_question_3)");

                    // Ejecutar la consulta con todos los valores correctamente vinculados
                    $stmt->execute([
                        ':username' => $username,
                        ':email' => $email,
                        ':password' => $hashedPassword,
                        ':role' => 'usuario',
                        ':is_active' => 1,
                        ':security_question_1' => $security_question_1,
                        ':security_question_2' => $security_question_2,
                        ':security_question_3' => $security_question_3
                    ]);

                    // Obtener el ID del nuevo usuario
                    $user_id = $pdo->lastInsertId();

                    // Registrar la acción en user_logs solo si $user_id está definido
                    if ($user_id) {
                        $log_stmt = $pdo->prepare("INSERT INTO user_logs (user_id, action, timestamp) VALUES (:user_id, :action, NOW())");
                        $log_stmt->execute([
                            ':user_id' => $user_id,
                            ':action' => 'create'
                        ]);

                    }

                    // Mensaje de éxito
                    $success = "Registro exitoso. Ahora puedes iniciar sesión.";
                } catch (PDOException $e) {
                    $error = "Error al registrar el usuario: " . $e->getMessage();
                }
            }
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}

// Función para validar que la contraseña cumpla con las reglas de robustez
function validate_password($password)
{
    return strlen($password) >= 8 &&         // Longitud mínima de 8 caracteres
        preg_match('/[A-Z]/', $password) && // Al menos una letra mayúscula
        preg_match('/[a-z]/', $password) && // Al menos una letra minúscula
        preg_match('/[0-9]/', $password) && // Al menos un número
        preg_match('/[\W_]/', $password);   // Al menos un carácter especial
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/register.css">
</head>

<body>
    <div class="register-container">
        <h2>Registro</h2>

        <!-- Mostrar mensaje de error o éxito si es necesario -->
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <label for="username">Nombre de usuario</label>
            <input type="text" name="username" id="username" required>

            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Contraseña:</label>
            <div class="password-container">
                <input type="password" name="password" id="password" required>
                <span class="toggle-password" onclick="togglePasswordVisibility('password', this)">👁️</span>
            </div>

            <label for="confirm_password">Confirmar contraseña</label>
            <div class="password-container">
                <input type="password" name="confirm_password" id="confirm_password" required>
                <span class="toggle-password" onclick="togglePasswordVisibility('confirm_password', this)">👁️</span>
            </div>

            <div>
                <label for="security_question_1">¿Cuál fue el nombre de la escuela a la que fuiste?</label>
                <div class="password-container">
                    <input type="password" id="security_question_1" name="security_question_1" required>
                    <span class="toggle-password"
                        onclick="togglePasswordVisibility('security_question_1', this)">👁️</span>
                </div>
            </div>

            <div>
                <label for="security_question_2">¿Cuál fue el nombre de tu primera mascota?</label>
                <div class="password-container">
                    <input type="password" id="security_question_2" name="security_question_2" required>
                    <span class="toggle-password"
                        onclick="togglePasswordVisibility('security_question_2', this)">👁️</span>
                </div>
            </div>

            <div>
                <label for="security_question_3">¿Cuál es tu película favorita?</label>
                <div class="password-container">
                    <input type="password" id="security_question_3" name="security_question_3" required>
                    <span class="toggle-password"
                        onclick="togglePasswordVisibility('security_question_3', this)">👁️</span>
                </div>
            </div>


            <input type="submit" value="Registrarse">

        </form>

        <div class="footer">
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
    </div>
</body>

</html>