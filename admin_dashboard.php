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
    <link rel="stylesheet" href="styles.css">
    <style>
        .header {
            background-color: #f0f0f0;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            font-size: 16px;
            font-weight: bold;
        }

        .logout {
            font-size: 14px;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .activate-btn {
            padding: 5px 10px;
            color: #fff;
            background-color: #28a745;
            text-decoration: none;
            border-radius: 4px;
        }

        .deactivate-btn {
            padding: 5px 10px;
            color: #fff;
            background-color: #dc3545;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="user-info">
            <?php echo htmlspecialchars($_SESSION['username']); ?><br>
            Rol: <?php echo htmlspecialchars($_SESSION['role']); ?>
        </div>
        <div class="logout">
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>

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
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user_id = $_POST['user_id'];
    
        // Conexión a la base de datos
        $conn = new mysqli('localhost', 'root', '', 'nombre_de_tu_base_de_datos');
    
        // Verificar conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
    
        // Eliminar usuario
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
    
        if ($stmt->execute()) {
            echo "Usuario eliminado correctamente.";
        } else {
            echo "Error al eliminar el usuario: " . $conn->error;
        }
    
        $stmt->close();
        $conn->close();
    
        // Redirigir de vuelta al panel de administración
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Método no permitido.";
    }
    ?>
    </table>
    </div>
    </body>
    
    <?php 
    ?>
    
    </html>