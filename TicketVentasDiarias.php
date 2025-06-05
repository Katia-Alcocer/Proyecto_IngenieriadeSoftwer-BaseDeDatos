<?php
require_once '../conexion.php';        
require_once '../tcpdf/tcpdf.php';     

$fecha = $_GET['fecha'] ?? date('Y-m-d');

// Consultar las ventas
$sql = "SELECT * FROM VistaVentasDiarias WHERE CAST(Fecha AS DATE) = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$fecha]);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar por empleado
$ventasPorEmpleado = [];
foreach ($ventas as $venta) {
    $ventasPorEmpleado[$venta['Empleado']][] = $venta;
}

// Crear PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Ventas');
$pdf->SetTitle('Ventas del Día - ' . $fecha);
$pdf->SetMargins(10, 20, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Ventas del Día - ' . $fecha, 0, 1, 'C');
$pdf->Ln(5);

if (empty($ventas)) {
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'No hay ventas registradas en esta fecha.', 0, 1, 'C');
} else {
    foreach ($ventasPorEmpleado as $empleado => $ventasEmpleado) {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'Empleado: ' . $empleado, 0, 1);
        $pdf->SetFont('helvetica', '', 10);

        // Encabezado de tabla
        $html = '
        <table border="1" cellpadding="4">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th><b>Venta</b></th>
                    <th><b>Cliente</b></th>
                    <th><b>Producto</b></th>
                    <th><b>Cant.</b></th>
                    <th><b>Precio</b></th>
                    <th><b>Subtotal</b></th>
                    <th><b>Hora</b></th>
                </tr>
            </thead>
            <tbody>';
        
        $sumaTotalEmpleado = 0;
        foreach ($ventasEmpleado as $venta) {
            $sumaTotalEmpleado += $venta['Subtotal'];
            $html .= '
                <tr>
                    <td>' . $venta['NumeroVenta'] . '</td>
                    <td>' . htmlspecialchars($venta['Cliente']) . '</td>
                    <td>' . htmlspecialchars($venta['Producto']) . '</td>
                    <td align="right">' . number_format($venta['Cantidad'], 2) . '</td>
                    <td align="right">$' . number_format($venta['PrecioUnitario'], 2) . '</td>
                    <td align="right">$' . number_format($venta['Subtotal'], 2) . '</td>
                    <td>' . $venta['Hora'] . '</td>
                </tr>';
        }

        $html .= '
            <tr style="background-color:#e0e0e0;">
                <td colspan="5" align="right"><b>Total del día:</b></td>
                <td colspan="2" align="right"><b>$' . number_format($sumaTotalEmpleado, 2) . '</b></td>
            </tr>
            </tbody>
        </table><br><br>';

        $pdf->writeHTML($html, true, false, true, false, '');
    }
}

// Descargar el PDF
$pdf->Output('Ventas_' . $fecha . '.pdf', 'D'); // 'D' = descarga directa
exit;
