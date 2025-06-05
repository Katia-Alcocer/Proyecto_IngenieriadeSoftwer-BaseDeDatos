<?php
require_once '../conexion.php';

try {
    
    $stmt = $pdo->query("SELECT idEmpleado, Contraseña FROM Empleados");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$usuarios) {
        echo "No se encontraron empleados.";
        exit;
    }

  
    foreach ($usuarios as $usuario) {
        $id = $usuario['idEmpleado'];
        $contrasenaPlano = $usuario['Contrasena'];

        
        if (strpos($contrasenaPlano, '$2y$') === 0) {
            echo "Empleado $id ya tiene contraseña hasheada. Saltando...<br>";
            continue;
        }

        
        $hash = password_hash($contrasenaPlano, PASSWORD_DEFAULT);

        
        $update = $pdo->prepare("UPDATE Empleados SET Contraseña = ? WHERE idEmpleado = ?");
        $update->execute([$hash, $id]);

        echo "Contraseña actualizada para empleado ID: $id<br>";
    }

    echo "<strong>Proceso completado.</strong>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
