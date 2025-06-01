<?php
$dsn = "sqlsrv:Server=sqlserver_container,1433;Database=master";
$user = "sa";
$password = "TuPassword123!";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "¡Conectado con PDO sqlsrv!";
} catch (PDOException $e) {
    die("Error de conexión PDO: " . $e->getMessage());
}
?>