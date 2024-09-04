<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'administrador') {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM audit_log");
$logs = $stmt->fetchAll();

foreach ($logs as $log) {
    echo "User ID: " . $log['user_id'] . " - Action: " . $log['action'] . " - Timestamp: " . $log['timestamp'] . "<br>";
}
?>