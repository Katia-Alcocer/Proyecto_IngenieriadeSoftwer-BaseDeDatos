<?php
require_once '../conexion.php';
require_once '../tcpdf/tcpdf.php'; // Asegúrate de que la ruta sea correcta según tu estructura

// Validar parámetro
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Proveedor no válido.");
}

$idProveedor = intval($_GET['id']);


try {
    // Obtener nombre del proveedor
    $stmtNombre = $pdo->prepare("SELECT Nombre FROM Vista_Proveedores WHERE idProveedor = :id");
    $stmtNombre->execute([':id' => $idProveedor]);
    $proveedor = $stmtNombre->fetch(PDO::FETCH_ASSOC);

    if (!$proveedor) {
        die("Proveedor no encontrado.");
    }

    // Obtener productos del proveedor
    $stmt = $pdo->prepare("EXEC ObtenerProductosPorProveedor @p_idProveedor = :idProveedor");
    $stmt->bindParam(':idProveedor', $idProveedor, PDO::PARAM_INT);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Crear PDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Tu Aplicación');
    $pdf->SetTitle("Productos del proveedor {$proveedor['Nombre']}");
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

    // Título
    $pdf->Cell(0, 10, 'Productos del proveedor: ' . $proveedor['Nombre'], 0, 1, 'C');
    $pdf->Ln(5);

    // Construir tabla en HTML
    $html = '<table border="1" cellpadding="4">
                <thead>
                    <tr>
                        <th><b>ID</b></th>
                        <th><b>Nombre</b></th>
                        <th><b>Compra</b></th>
                        <th><b>Venta</b></th>
                        <th><b>Stock</b></th>
                        <th><b>Estado</b></th>
                        <th><b>ID Categoría</b></th>
                    </tr>
                </thead>
                <tbody>';
    foreach ($productos as $prod) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($prod['idProducto']) . '</td>
                    <td>' . htmlspecialchars($prod['Nombre']) . '</td>
                    <td>$' . number_format($prod['PrecioCompra'], 2) . '</td>
                    <td>$' . number_format($prod['PrecioVenta'], 2) . '</td>
                    <td>' . htmlspecialchars($prod['Stock']) . '</td>
                    <td>' . htmlspecialchars($prod['Estado']) . '</td>
                    <td>' . htmlspecialchars($prod['idCategoria']) . '</td>
                  </tr>';
    }
    $html .= '</tbody></table>';

    // Escribir tabla HTML en el PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Salida del PDF en navegador
    $pdf->Output("productos_proveedor_{$idProveedor}.pdf", 'I');

} catch (PDOException $e) {
    die("Error de base de datos: " . $e->getMessage());
}
