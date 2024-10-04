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
                // Comparar respuestas con los hashes almacenados en la base de datos usando password_verify
                if (
                    password_verify($answer1, $user['security_question_1']) &&
                    password_verify($answer2, $user['security_question_2']) &&
                    password_verify($answer3, $user['security_question_3'])
                ) {
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
    <link rel="stylesheet" href="css/process_password_reset.css">
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