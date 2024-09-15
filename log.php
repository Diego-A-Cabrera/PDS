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
<html>
<head>
    <title>Registro de Usuarios</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Registro de Actividad de Usuarios</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>Acción</th>
            <th>Fecha y Hora</th>
        </tr>
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
    </table>
</body>
</html>