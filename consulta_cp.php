<?php
require_once '../conexion.php'; // Ajusta la ruta si es necesario

if (isset($_GET['cp'])) {
    $cp = $_GET['cp'];

    try {
        $stmt = $pdo->prepare("EXEC ObtenerAsentamientosPorCP @cp = ?");
        $stmt->execute([$cp]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($result);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al consultar los asentamientos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode([]);
}
?>
