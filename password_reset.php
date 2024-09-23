<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña</title>
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

        .reset-container {
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

        input[type="text"],
        input[type="email"] {
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

        .success-message {
            color: #4F8A10;
            background-color: #DFF2BF;
            border: 1px solid #4F8A10;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
    </style>
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
