<?php
session_start();

// Verificar si el usuario es administrador
if ($_SESSION['role'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
include 'db.php';

// Variables de filtro
$username_filter = isset($_GET['username']) ? $_GET['username'] : '';
$email_filter = isset($_GET['email']) ? $_GET['email'] : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

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

// Construir la consulta SQL con filtros dinámicos
$query = "SELECT id, username, email, is_active, role FROM users WHERE 1=1";

if ($username_filter) {
    $query .= " AND username LIKE :username";
}
if ($email_filter) {
    $query .= " AND email LIKE :email";
}
if ($role_filter) {
    $query .= " AND role = :role";
}
if ($status_filter !== '') {
    $query .= " AND is_active = :status";
}

// Preparar la consulta
$stmt = $pdo->prepare($query);

// Asignar parámetros
if ($username_filter) {
    $username_filter = '%' . $username_filter . '%';
    $stmt->bindParam(':username', $username_filter, PDO::PARAM_STR);
}
if ($email_filter) {
    $email_filter = '%' . $email_filter . '%';
    $stmt->bindParam(':email', $email_filter, PDO::PARAM_STR);
}
if ($role_filter) {
    $stmt->bindParam(':role', $role_filter, PDO::PARAM_STR);
}
if ($status_filter !== '') {
    $stmt->bindParam(':status', $status_filter, PDO::PARAM_INT);
}

// Ejecutar la consulta
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin_dashboard.css">
</head>

<form>

    <header class="header">
        <div class="user-info">
            <?php echo htmlspecialchars($_SESSION['username']); ?><br>
            Rol: <?php echo htmlspecialchars($_SESSION['role']); ?>
        </div>
        <div class="buttons">
            <a href="log.php">Ver registros de usuarios</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </header>

    <h1>Panel de Administración</h1>

    <!-- Formulario de filtros -->
    <div class="filter-container">
        <form method="GET" action="admin_dashboard.php">
            <div>
                <label for="username">Filtrar por Nombre de Usuario:</label>
                <input type="text" name="username" id="username"
                    value="<?php echo htmlspecialchars($username_filter); ?>">
            </div>

            <div>
                <label for="email">Filtrar por Email:</label>
                <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($email_filter); ?>">
            </div>

            <div>
                <label for="role">Filtrar por Rol:</label>
                <select name="role" id="role">
                    <option value="">Seleccionar Rol</option>
                    <option value="usuario" <?php if ($role_filter === 'usuario')
                        echo 'selected'; ?>>Usuario</option>
                    <option value="administrador" <?php if ($role_filter === 'administrador')
                        echo 'selected'; ?>>
                        Administrador</option>
                </select>
            </div>

            <div>
                <label for="status">Filtrar por Estado:</label>
                <select name="status" id="status">
                    <option value="">Seleccionar Estado</option>
                    <option value="1" <?php if ($status_filter === '1')
                        echo 'selected'; ?>>Activo</option>
                    <option value="0" <?php if ($status_filter === '0')
                        echo 'selected'; ?>>Bloqueado</option>
                </select>
            </div>

            <div>
                <input type="submit" value="Aplicar Filtros">
            </div>
    </div>
</form>

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