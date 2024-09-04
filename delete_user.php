<?php
include 'db.php'; // Incluye el archivo con la conexión a la base de datos

// Verificar si user_id está establecido
if (!isset($_POST['user_id'])) {
    echo "Error: user_id no está establecido.";
    exit();
}

$user_id = $_POST['user_id'];

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'nombre_de_tu_base_de_datos');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete user
$sql = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);

if ($stmt->execute()) {
    echo "User deleted successfully.";
} else {
    echo "Error deleting user: " . $conn->error;
}

$stmt->close();
$conn->close();

// Redirect back to the admin dashboard
header("Location: admin_dashboard.php");
exit();
?>
