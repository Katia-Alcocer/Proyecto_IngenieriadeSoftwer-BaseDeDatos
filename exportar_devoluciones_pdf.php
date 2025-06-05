<?php
require '../conexion.php';
require_once '../tcpdf/tcpdf.php';


try {
    $stmt = $pdo->query("SELECT * FROM vw_Devoluciones");
    $devoluciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar: " . $e->getMessage());
}

// Crear PDF
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator('Tu Sistema');
$pdf->SetAuthor('Sistema de Ventas');
$pdf->SetTitle('Reporte de Devoluciones');
$pdf->SetMargins(10, 15, 10);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Devoluciones Registradas', 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 10);

$html = '<table border="1" cellpadding="4">
<thead>
<tr style="background-color:#f2f2f2;">
  <th>ID</th><th>Fecha</th><th>Hora</th><th>ID Venta</th>
  <th>ID Producto</th><th>Producto</th><th>Cantidad</th>
  <th>Motivo</th><th>ID Empleado</th><th>Empleado</th>
</tr>
</thead><tbody>';

foreach ($devoluciones as $dev) {
    $html .= '<tr>
        <td>' . htmlspecialchars($dev['idDevolucion']) . '</td>
        <td>' . htmlspecialchars($dev['Fecha']) . '</td>
        <td>' . htmlspecialchars($dev['Hora']) . '</td>
        <td>' . htmlspecialchars($dev['idVenta']) . '</td>
        <td>' . htmlspecialchars($dev['idProducto']) . '</td>
        <td>' . htmlspecialchars($dev['NombreProducto']) . '</td>
        <td>' . htmlspecialchars($dev['CantidadDevuelta']) . '</td>
        <td>' . htmlspecialchars($dev['Motivo']) . '</td>
        <td>' . htmlspecialchars($dev['idEmpleado']) . '</td>
        <td>' . htmlspecialchars($dev['NombreEmpleado']) . '</td>
    </tr>';
}
$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Devoluciones_Registradas.pdf', 'D');
exit;
