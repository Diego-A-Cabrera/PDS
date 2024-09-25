<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Verificar el rol del usuario
$isAdmin = $_SESSION['role'] === 'administrador';
$isUser = $_SESSION['role'] === 'usuario';

// Si el rol no es ni administrador ni usuario, mostrar un mensaje de error
if (!$isAdmin && !$isUser) {
    echo "No tienes permiso para ver este contenido.";
    exit();
}

// Inicializar filtros
$username_filter = isset($_GET['username']) ? $_GET['username'] : '';
$email_filter = isset($_GET['email']) ? $_GET['email'] : '';
?>

<?php
include 'db.php';

// Verificar si hay filtros
$sql = "SELECT id, username, email, password, role, created_at FROM users WHERE 1=1";
$params = [];

// Filtrar por username si está presente
if (!empty($username_filter)) {
    $sql .= " AND username LIKE :username";
    $params[':username'] = '%' . $username_filter . '%';
}

// Filtrar por email si está presente
if (!empty($email_filter)) {
    $sql .= " AND email LIKE :email";
    $params[':email'] = '%' . $email_filter . '%';
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilo del encabezado y la navegación */
        .header {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
            border-bottom: 1px solid #444;
            position: fixed;
            z-index: 10;
            top: 0;
            left: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1c1e22;
            color: white;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #4a76a8;
            margin-top: 120px;
        }

        .buttons {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .buttons a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 20px;
            background-color: #4a76a8;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .buttons a:hover {
            background-color: #3c5a7b;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
        }

        .content {
            margin-top: 20px;
        }

        .table-container {
            margin-top: 20px;
            background-color: #2c2f33;
            border-radius: 8px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: white;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #444;
        }

        th {
            background-color: #23272a;
        }

        tr:hover {
            background-color: #3c3f45;
        }

        /* Estilo de la sección de filtros */
        .filter-container {
            display: flex;
            justify-content: center;
            background-color: #2c2f33;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .filter-container form {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            max-width: 800px;
            width: 100%;
        }

        .filter-container div {
            flex: 1;
            margin: 0 10px;
            min-width: 200px;
        }

        .filter-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .filter-container input[type="text"] {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #444;
            background-color: #23272a;
            color: white;
        }

        .filter-container input[type="submit"] {
            margin-top: 25px;
            padding: 10px;
            background-color: #4a76a8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .filter-container input[type="submit"]:hover {
            background-color: #3c5a7b;
        }

    </style>
</head>

<body>

    <div class="header">
        <div class="user-info">
            Usuario: <?php echo htmlspecialchars($_SESSION['username']); ?><br>
            Rol: <?php echo htmlspecialchars($_SESSION['role']); ?>
        </div>
        <div class="buttons">
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>

   

        <div class="content">
            <h1>Panel de Usuario</h1>
            <div class="container">
        <div class="filter-container">
            <form method="GET" action="dashboard.php">
                <div>
                    <label for="username">Filtrar por Nombre de Usuario:</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username_filter); ?>">
                </div>

                <div>
                    <label for="email">Filtrar por Email:</label>
                    <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($email_filter); ?>">
                </div>

                <div>
                    <input type="submit" value="Aplicar Filtros">
                </div>
            </form>
        </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <?php if ($isAdmin): ?>
                                <th>#</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Role</th>
                                <th>Created At</th>
                            <?php elseif ($isUser): ?>
                                <th>#</th>
                                <th>Email</th>
                                <th>Password</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Llenado dinámico de la tabla -->
                        <?php
                        foreach ($users as $row) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                            if ($isAdmin) {
                                echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                            }
                            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                            echo '<td>********</td>'; // Contraseña oculta
                            if ($isAdmin) {
                                echo '<td>' . htmlspecialchars($row['role']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
