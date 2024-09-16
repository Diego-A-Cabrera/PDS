<?php
include 'db.php'; // Incluye la conexión a la base de datos
session_start(); // Inicia la sesión

// Verificar si el usuario está logueado, si no redirigir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Variables para almacenar los filtros
$username_filter = isset($_GET['username']) ? $_GET['username'] : '';
$action_filter = isset($_GET['action']) ? $_GET['action'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Construir la consulta SQL dinámica con filtros
$query = "SELECT user_logs.*, users.username FROM user_logs 
          JOIN users ON user_logs.user_id = users.id WHERE 1=1";

// Agregar filtros si existen
if ($username_filter) {
    $query .= " AND users.username LIKE :username";
}
if ($action_filter) {
    $query .= " AND action = :action";
}
if ($date_from && $date_to) {
    $query .= " AND timestamp BETWEEN :date_from AND :date_to";
}

// Ordenar de más reciente a más antiguo
$query .= " ORDER BY timestamp DESC";

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($query);

// Asignar parámetros a la consulta
if ($username_filter) {
    $stmt->bindParam(':username', $username_filter, PDO::PARAM_STR);
}
if ($action_filter) {
    $stmt->bindParam(':action', $action_filter, PDO::PARAM_STR);
}
if ($date_from && $date_to) {
    $stmt->bindParam(':date_from', $date_from);
    $stmt->bindParam(':date_to', $date_to);
}

// Ejecutar la consulta
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs de Usuarios</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1c1e22;
            color: white;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #333;
        }

        th {
            background-color: #4a76a8;
            color: white;
        }

        td {
            background-color: #2c2f33;
        }

        .filter-container {
            background-color: #2c2f33;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        label {
            color: white;
        }

        input[type="text"],
        input[type="date"],
        select {
            padding: 8px;
            margin-right: 10px;
            border-radius: 5px;
            background-color: #23272a;
            color: white;
            border: none;
        }

        input[type="submit"] {
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

        /* Estilo para los botones en el margen superior derecho */
        .btn-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .btn {
            padding: 10px;
            background-color: #4a76a8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            margin-left: 10px;
        }

        .btn:hover {
            background-color: #3c5a7b;
        }
    </style>
</head>

<body>

    <!-- Botones de navegación en el margen superior derecho -->
    <div class="btn-container">
        <a href="admin_dashboard.php" class="btn">Volver al Panel de Administración</a>
        <a href="logout.php" class="btn">Cerrar Sesión</a>
    </div>

    <h2>Logs de Usuarios</h2>

    <div class="filter-container">
        <form method="GET" action="log.php">
            <!-- Filtro por nombre de usuario -->
            <label for="username">Filtrar por Nombre de Usuario:</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username_filter); ?>">

            <!-- Filtro por acción -->
            <label for="action">Filtrar por Acción:</label>
            <select name="action" id="action">
                <option value="">Seleccionar acción</option>
                <option value="login" <?php if ($action_filter === 'login')
                    echo 'selected'; ?>>Login</option>
                <option value="failed login" <?php if ($action_filter === 'failed login')
                    echo 'selected'; ?>>Failed Login
                </option>
                <option value="logout" <?php if ($action_filter === 'logout')
                    echo 'selected'; ?>>Logout</option>
            </select>

            <!-- Filtro por rango de fechas -->
            <label for="date_from">Desde:</label>
            <input type="date" name="date_from" id="date_from" value="<?php echo htmlspecialchars($date_from); ?>">

            <label for="date_to">Hasta:</label>
            <input type="date" name="date_to" id="date_to" value="<?php echo htmlspecialchars($date_to); ?>">

            <!-- Botón para aplicar filtros -->
            <input type="submit" value="Aplicar Filtros">
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre de Usuario</th>
                <th>Acción</th>
                <th>Fecha y Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo htmlspecialchars($log['username']); ?></td>
                    <td><?php echo htmlspecialchars($log['action']); ?></td>
                    <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>