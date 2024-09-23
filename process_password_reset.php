<?php
include 'db.php'; // Incluye la conexión a la base de datos
session_start(); // Inicia la sesión

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $email = trim($_POST['email']);
    $answer1 = trim($_POST['answer1']);
    $answer2 = trim($_POST['answer2']);
    $answer3 = trim($_POST['answer3']);

    // Validar que todos los campos estén completos
    if (!empty($email) && !empty($answer1) && !empty($answer2) && !empty($answer3)) {
        try {
            // Consulta a la base de datos para obtener las preguntas de seguridad
            $stmt = $pdo->prepare("SELECT security_question_1, security_question_2, security_question_3 FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Comparar respuestas en formato no sensible a mayúsculas/minúsculas
                if (strcasecmp($answer1, $user['security_question_1']) == 0 &&
                    strcasecmp($answer2, $user['security_question_2']) == 0 &&
                    strcasecmp($answer3, $user['security_question_3']) == 0) {

                    // Respuestas correctas, redirigir a la página para cambiar contraseña
                    $_SESSION['reset_email'] = $email;
                    header("Location: update_password.php");
                    exit;
                } else {
                    $error = "Las respuestas a las preguntas de seguridad no coinciden.";
                }
            } else {
                $error = "No se encontró ningún usuario con ese correo electrónico.";
            }
        } catch (PDOException $e) {
            $error = "Error al verificar respuestas: " . $e->getMessage();
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1c1e22;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
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
        input[type="email"],
        input[type="text"],
        input[type="password"] {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Restablecer Contraseña</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="process_password_reset.php">
            <label for="email">Correo Electrónico</label>
            <input type="email" name="email" id="email" required>

            <label for="answer1">Respuesta a la pregunta 1</label>
            <input type="text" name="answer1" id="answer1" required>

            <label for="answer2">Respuesta a la pregunta 2</label>
            <input type="text" name="answer2" id="answer2" required>

            <label for="answer3">Respuesta a la pregunta 3</label>
            <input type="text" name="answer3" id="answer3" required>

            <input type="submit" value="Enviar">
        </form>
    </div>
</body>
</html>