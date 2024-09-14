<?php
session_start();
require 'db.php'; // Incluir conexión a la base de datos con PDO

// Consulta para obtener todas las acciones de los usuarios (incluyendo creación) ordenadas cronológicamente
$query = "SELECT u.username, u.created_at, ul.action, 
                 CASE 
                    WHEN ul.timestamp IS NOT NULL THEN ul.timestamp 
                    ELSE u.created_at 
                 END AS action_time
          FROM users u
          LEFT JOIN user_logs ul ON u.id = ul.user_id
          ORDER BY action_time DESC";

$stmt = $pdo->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_styles.css">
    <title>Registros de Usuarios</title>
</head>

<body>
    <h1>Registros de Actividades de Usuarios</h1>
    <table border="1">
        <tr>
            <th>Usuario</th>
            <th>Acción</th>
            <th>Fecha de Acción</th>
        </tr>
        <?php
        if ($results) {
            foreach ($results as $row) {
                echo "<tr>
                        <td>{$row['username']}</td>";

                // Si no hay acción registrada, mostrar "Registro" con la fecha de creación del usuario
                if (empty($row['action'])) {
                    echo "<td>Registro</td>
                          <td>{$row['created_at']}</td>";
                } else {
                    // Mostrar otras acciones (login, logout, bloqueo, etc.) con su respectiva fecha
                    echo "<td>{$row['action']}</td>
                          <td>{$row['action_time']}</td>";
                }

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay registros disponibles</td></tr>";
        }
        ?>
    </table>
</body>

</html>