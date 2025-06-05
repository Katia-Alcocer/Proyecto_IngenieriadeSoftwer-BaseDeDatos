<?php
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCliente = $_POST['idCliente'] ?? null;
    $idEmpleado = $_POST['idEmpleado'] ?? null;
    $fechaVenta = $_POST['fechaVenta'] ?? date('Y-m-d');

    if ($idCliente && $idEmpleado) {
        try {
            // Ejecutar el procedimiento con nombres correctos
            $stmt = $pdo->prepare("
                EXEC RealizarVentaSimplificada 
                    @p_idCliente = :p_idCliente, 
                    @p_idEmpleado = :p_idEmpleado, 
                    @p_fecha = :p_fecha
            ");
            $stmt->bindParam(':p_idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->bindParam(':p_idEmpleado', $idEmpleado, PDO::PARAM_INT);
            $stmt->bindParam(':p_fecha', $fechaVenta);
            $stmt->execute();

            echo " Venta registrada exitosamente.";
        } catch (PDOException $e) {
            echo " Error al procesar la venta: " . $e->getMessage();
        }
    } else {
        echo "⚠️ Faltan datos requeridos.";
    }
}
?>
