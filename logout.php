<?php
session_start();

// Incluir la conexión a la base de datos
require 'db.php';  // Asegúrate de que esta ruta sea correcta

$userId = $_SESSION['user_id'];  // Asegúrate de que el user_id está en la sesión

// Registrar en el log de cierre de sesión en la tabla user_logs
$logStmt = $pdo->prepare("INSERT INTO user_logs (user_id, action, timestamp) VALUES (:user_id, 'logout', NOW())");
$logStmt->execute(['user_id' => $userId]);

// Destruir la sesión
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>