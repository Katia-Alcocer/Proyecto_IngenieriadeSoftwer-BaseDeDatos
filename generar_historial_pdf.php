<?php
require_once '../conexion.php';
require_once '../tcpdf/tcpdf.php'; // Asegúrate de que TCPDF esté en esta ruta

try {
    $stmt = $pdo->query("SELECT * FROM vw_HistorialModificaciones");
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar: " . $e->getMessage());
}

// Crear nuevo PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Control');
$pdf->SetTitle('Historial de Modificaciones');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// Encabezado
$html = '<h2 style="text-align:center;">Historial de Modificaciones</h2>
<table border="1" cellspacing="0" cellpadding="4">
<thead>
<tr style="background-color:#f2f2f2;">
  <th>ID</th>
  <th>Movimiento</th>
  <th>Tabla</th>
  <th>Columna</th>
  <th>Anterior</th>
  <th>Nuevo</th>
  <th>Fecha</th>
  <th>Hora</th>
  <th>Empleado</th>
</tr>
</thead><tbody>';

// Filas
foreach ($datos as $fila) {
    $html .= '<tr>
      <td>' . htmlspecialchars($fila['ID']) . '</td>
      <td>' . htmlspecialchars($fila['Movimiento']) . '</td>
      <td>' . htmlspecialchars($fila['TablaAfectada']) . '</td>
      <td>' . htmlspecialchars($fila['ColumnaAfectada']) . '</td>
      <td>' . htmlspecialchars($fila['ValorAnterior']) . '</td>
      <td>' . htmlspecialchars($fila['ValorNuevo']) . '</td>
      <td>' . htmlspecialchars($fila['Fecha']) . '</td>
      <td>' . htmlspecialchars($fila['Hora']) . '</td>
      <td>' . htmlspecialchars($fila['idEmpleado']) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('HistorialModificaciones.pdf', 'D');
exit;
