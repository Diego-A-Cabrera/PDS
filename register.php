<?php
include 'db.php'; // Incluye el archivo con la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']); // Obtener el rol

    // Validar campos
    $errors = [];

    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $errors[] = "Todos los campos son requeridos.";
    } else {
        // Verificar si el nombre de usuario o el correo electrónico ya existen
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $username, 'email' => $email]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "El nombre de usuario o el correo electrónico ya están en uso.";
            }

            // Verificar la seguridad de la contraseña
            if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
                $errors[] = "La contraseña debe tener al menos 8 caracteres, incluyendo una letra mayúscula, un número y un símbolo.";
            }

            // Si no hay errores, insertar el nuevo usuario
            if (empty($errors)) {
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
                $stmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password' => $passwordHash,
                    'role' => $role
                
                ]);

                 // Obtener el último ID de usuario insertado
                $userId = $pdo->lastInsertId();


                // Insertar en user_logs el log del usuario creado
                $stmt = $pdo->prepare("INSERT INTO user_logs (user_id, action) VALUES (:user_id, 'create')");
                $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                
                $stmt->execute();
                // Redirigir o mostrar mensaje de éxito
                header("Location: login.php?success=1");
                exit();
            }
        } catch (PDOException $e) {
            $errors[] = "Error al registrar el usuario: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
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
    <div class="register-container">
        <h2>Registro</h2>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <div class="input-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="input-group">
                <label for="role">Perfil</label>
                <select name="role" id="role" required>
                    <option value="usuario">Usuario</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn-register">Registrarse</button>
        </form>
    </div>
</body>

</html>