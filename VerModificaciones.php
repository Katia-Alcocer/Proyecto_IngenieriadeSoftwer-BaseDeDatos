<?php
require_once '../conexion.php';

try {
    $stmt = $pdo->query("SELECT * FROM vw_HistorialModificaciones");
    $modificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar la vista: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Modificaciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-5 text-uppercase fw-bold" style="font-size: 2.8rem; color: #fff; text-shadow: 2px 2px 6px rgba(0,0,0,0.7); letter-spacing: 1px;">
    Historial de Modificaciones
  </h2>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Movimiento</th>
          <th>Tabla Afectada</th>
          <th>Columna Afectada</th>
          <th>Valor Anterior</th>
          <th>Valor Nuevo</th>
          <th>Fecha</th>
          <th>Hora</th>
          <th>ID Empleado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($modificaciones as $mod): ?>
          <tr>
            <td><?= htmlspecialchars($mod['ID']) ?></td>
            <td><?= htmlspecialchars($mod['Movimiento']) ?></td>
            <td><?= htmlspecialchars($mod['TablaAfectada']) ?></td>
            <td><?= htmlspecialchars($mod['ColumnaAfectada']) ?></td>
            <td><?= htmlspecialchars($mod['ValorAnterior']) ?></td>
            <td><?= htmlspecialchars($mod['ValorNuevo']) ?></td>
            <td><?= htmlspecialchars($mod['Fecha']) ?></td>
            <td><?= htmlspecialchars($mod['Hora']) ?></td>
            <td><?= htmlspecialchars($mod['idEmpleado']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <br>

  <div class="d-flex justify-content-start gap-3 mt-4">
   
    <button type="button" class="btn text-white px-4 py-2" 
      style="background: linear-gradient(135deg, #2c5364, #203a43, #0f2027); border: none; font-size: 1.1rem;"
      onclick="window.location.href='pagina1.php'">
      Volver
    </button>
    
<form action="generar_historial_pdf.php" method="post">
  <button type="submit" class="btn text-white px-4 py-2"
    style="background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); border: none; font-size: 1.1rem;">
    Descargar PDF
  </button>
</form>

  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
