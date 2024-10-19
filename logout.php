<?php
session_start();
require 'db.php'; // Incluye el archivo con la conexión a la base de datos

$userId = $_SESSION['user_id'];

// Registrar en el log de cierre de sesión en la tabla user_logs
$logStmt = $pdo->prepare("INSERT INTO user_logs (user_id, action, timestamp) VALUES (:user_id, 'logout', NOW())");
$logStmt->execute(['user_id' => $userId]);

// Destruir la sesión
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>