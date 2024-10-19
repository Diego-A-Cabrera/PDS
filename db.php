<?php
$dsn = 'mysql:host=localhost;dbname=pds';
$username = 'root';
$password = '';

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO($dsn, $username, $password);

    // Configurar el modo de error de PDO para que lance excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Mostrar mensaje de error en caso de fallo en la conexión
    echo "Error de conexión: " . $e->getMessage();
    // Terminar la ejecución del script si no se puede conectar
    exit();
}
?>