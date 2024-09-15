<?php
session_start();
require 'db.php'; // Incluir conexión a la base de datos con PDO

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener los registros del log de usuarios
    $sql = "SELECT users.username, user_logs.action, user_logs.timestamp 
            FROM user_logs 
            JOIN users ON user_logs.user_id = users.id
            ORDER BY user_logs.timestamp DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    // Obtener los resultados
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    die(); // Detiene la ejecución en caso de error
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #2c2f33; /* Fondo oscuro */
            color: #ffffff; /* Texto blanco */
            margin: 0;
            padding: 20px;
        }

        .header {
            background-color: #23272a; /* Franja oscura */
            padding: 15px;
            display: flex;
            justify-content: flex-end; /* Alineación a la derecha */
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 10;
            border-bottom: 1px solid #444;
            box-sizing: border-box;
        }

        h2 {
            color: #7289da; /* Azul moderno */
            margin-top: 80px; /* Margen para evitar el header fijo */
            text-align: center;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        .buttons a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #7289da; /* Botón azul moderno */
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .buttons a:hover {
            background-color: #5865f2; /* Azul más claro al pasar el ratón */
        }

        .table-container {
            margin-top: 80px; /* Margen para evitar el header fijo */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2f3136; /* Fondo oscuro para la tabla */
            color: #ffffff; /* Texto blanco */
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #444; /* Borde gris oscuro */
        }

        th {
            background-color: #23272a; /* Fondo oscuro para encabezados */
            color: #7289da; /* Texto azul para encabezados */
        }

        tr:hover {
            background-color: #3a3f44; /* Fondo más claro al pasar el ratón */
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="buttons">
            <!-- Botón de volver al panel de administración -->
            <a href="admin_dashboard.php">Volver al Panel de Administración</a>
            <!-- Botón de cerrar sesión -->
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </header>

    <h2>Registro de Actividad de Usuarios</h2>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Acción</th>
                    <th>Fecha y Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($logs)) {
                    // Mostrar los datos de cada fila
                    foreach ($logs as $row) {
                        echo "<tr><td>" . htmlspecialchars($row["username"]) . "</td><td>" . htmlspecialchars($row["action"]) . "</td><td>" . htmlspecialchars($row["timestamp"]) . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No hay registros</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>
