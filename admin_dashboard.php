<?php
session_start();

// Verificar si el usuario es administrador
if ($_SESSION['role'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
include 'db.php';

// Cambiar el estado de la cuenta si se envía una solicitud de activación/desbloqueo
if (isset($_GET['toggle_active']) && isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    // Obtén el estado actual del usuario antes de actualizarlo
    $stmt = $pdo->prepare("SELECT is_active FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $currentState = $stmt->fetchColumn();

    // Cambia el estado del usuario
    $stmt = $pdo->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
    $stmt->execute([$userId]);

    // Define la acción basada en el estado anterior
    $action = ($currentState == 1) ? 'block' : 'unblock';

    // Insertar log
    $logStmt = $pdo->prepare("INSERT INTO user_logs (user_id, action) VALUES (?, ?)");
    $logStmt->execute([$userId, $action]);

    header("Location: admin_dashboard.php");
    exit();
}

// Obtener todos los usuarios
$stmt = $pdo->query("SELECT id, username, email, is_active, role FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador</title>
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
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 10;
            border-bottom: 1px solid #444;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: #7289da; /* Azul moderno */
            margin-top: 80px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-right: 20px;
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .user-info {
            font-size: 16px;
            font-weight: bold;
            color: #99aab5; /* Texto gris claro */
        }

        .table-container {
            margin-top: 120px; /* Aumentar el margen para evitar que la tabla quede tapada por el header */
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

        .activate-btn {
            padding: 5px 10px;
            color: #fff;
            background-color: #43b581; /* Verde activado */
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .deactivate-btn {
            padding: 5px 10px;
            color: #fff;
            background-color: #f04747; /* Rojo desactivado */
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .activate-btn:hover {
            background-color: #34a556; /* Verde más claro al pasar el ratón */
        }

        .deactivate-btn:hover {
            background-color: #d32e2e; /* Rojo más claro al pasar el ratón */
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="user-info">
            <?php echo htmlspecialchars($_SESSION['username']); ?><br>
            Rol: <?php echo htmlspecialchars($_SESSION['role']); ?>
        </div>
        <div class="buttons">
            <!-- Botón de ver registros de usuarios -->
            <a href="log.php">Ver registros de usuarios</a>
            <!-- Botón de cerrar sesión -->
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </header>

    <h1>Panel de Administración</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><?php echo $user['is_active'] ? 'Activo' : 'Bloqueado'; ?></td>
                        <td>
                            <a href="admin_dashboard.php?toggle_active=1&id=<?php echo $user['id']; ?>"
                                class="<?php echo $user['is_active'] ? 'deactivate-btn' : 'activate-btn'; ?>">
                                <?php echo $user['is_active'] ? 'Bloquear' : 'Activar'; ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
