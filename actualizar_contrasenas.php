<?php
require_once '../conexion.php';

try {
    // 1. Leer todos los empleados
    $stmt = $pdo->query("SELECT idEmpleado, Contraseña FROM Empleados");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$usuarios) {
        echo "No se encontraron empleados.";
        exit;
    }

    // 2. Recorrer cada uno
    foreach ($usuarios as $usuario) {
        $id = $usuario['idEmpleado'];
        $contrasenaPlano = $usuario['Contrasena'];

        // Omitir si ya está hasheada (opcional pero recomendable)
        if (strpos($contrasenaPlano, '$2y$') === 0) {
            echo "Empleado $id ya tiene contraseña hasheada. Saltando...<br>";
            continue;
        }

        // 3. Hashear la contraseña
        $hash = password_hash($contrasenaPlano, PASSWORD_DEFAULT);

        // 4. Actualizar la base de datos
        $update = $pdo->prepare("UPDATE Empleados SET Contraseña = ? WHERE idEmpleado = ?");
        $update->execute([$hash, $id]);

        echo "Contraseña actualizada para empleado ID: $id<br>";
    }

    echo "<strong>Proceso completado.</strong>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
