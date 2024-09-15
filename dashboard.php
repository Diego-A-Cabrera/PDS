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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
     <!-- Estilos tomados de admin_dashboard -->
     <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        /* Estilo para la franja gris que ocupa todo el ancho */
        .header {
            width: 100%;
            background-color: #e0e0e0;
            /* Gris claro */
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
            border-bottom: 1px solid #ccc;
            position: fixed;
            z-index: 10;
            top: 0;
            left: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #4a76a8;
            margin-top: 60px;
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
            max-width: 1200px;
            margin: 0 auto;
        }

        .content {
            margin-top: 60px;
            /* Margen superior para evitar que el contenido quede oculto debajo de la franja */
        }

        .user-info {
            font-size: 16px;
            font-weight: bold;
        }

        .logout {
            font-size: 14px;
        }

        /* Estilo de la tabla y el contenedor */
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
    </style>
</head>

<body>
    <div class="header">
        <div class="user-info">
            Usuario:<?php echo htmlspecialchars($_SESSION['username']); ?><br>
            Rol: <?php echo htmlspecialchars($_SESSION['role']); ?>
        </div>
        <div class="buttons">
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>

    

    <div class="content">
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
                    <!-- Aquí se rellenarán las filas de la tabla con los datos de la base de datos -->
                    <?php
                    // Incluye el archivo de conexión a la base de datos
                    include 'db.php';

                    try {
                        if ($isAdmin) {
                            $stmt = $pdo->query("SELECT id, username, email, password, role, created_at FROM users");
                        } elseif ($isUser) {
                            $stmt = $pdo->query("SELECT id, email, password FROM users");
                        }

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>