<?php
/* $dsn = 'mysql:host=localhost;dbname=auth_system'; // Ajusta el nombre de la base de datos y el host según sea necesario
$username = 'tu_usuario'; // Tu usuario de base de datos
$password = 'tu_contraseña'; // Tu contraseña de base de datos */
$dsn = 'mysql:host=localhost;dbname=pds'; // Ajusta el nombre de la base de datos y el host según sea necesario
$username = 'root'; // Tu usuario de base de datos
$password = ''; // Tu contraseña de base de datos 

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO($dsn, $username, $password);

    // Configurar el modo de error de PDO para que lance excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mensaje de éxito (opcional)
    // echo "Conexión exitosa.";

} catch (PDOException $e) {
    // Mostrar mensaje de error en caso de fallo en la conexión
    echo "Error de conexión: " . $e->getMessage();
    // Terminar la ejecución del script si no se puede conectar
    exit();
}
?>