<?php
require_once '../conexion.php';

try {
    $stmt = $pdo->query("SELECT * FROM vw_Devoluciones");
    $devoluciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar la vista: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Devoluciones Registradas</title>
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
  <h2 class="mb-5 text-uppercase fw-bold" style="font-size: 3rem; color: #fff; text-shadow: 2px 2px 6px rgba(0,0,0,0.7); letter-spacing: 2px;">
    ¡Devoluciones Registradas!
  </h2>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID Devolución</th>
          <th>Fecha</th>
          <th>Hora</th>
          <th>ID Venta</th>
          <th>ID Producto</th>
          <th>Producto</th>
          <th>Cantidad Devuelta</th>
          <th>Motivo</th>
          <th>ID Empleado</th>
          <th>Empleado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($devoluciones as $dev): ?>
          <tr>
            <td><?= htmlspecialchars($dev['idDevolucion']) ?></td>
            <td><?= htmlspecialchars($dev['Fecha']) ?></td>
            <td><?= htmlspecialchars($dev['Hora']) ?></td>
            <td><?= htmlspecialchars($dev['idVenta']) ?></td>
            <td><?= htmlspecialchars($dev['idProducto']) ?></td>
            <td><?= htmlspecialchars($dev['NombreProducto']) ?></td>
            <td><?= htmlspecialchars($dev['CantidadDevuelta']) ?></td>
            <td><?= htmlspecialchars($dev['Motivo']) ?></td>
            <td><?= htmlspecialchars($dev['idEmpleado']) ?></td>
            <td><?= htmlspecialchars($dev['NombreEmpleado']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <br>

  <div class="d-flex justify-content-start gap-3 mt-4">
    <!-- Botón Volver -->
    <button type="button" class="btn text-white px-4 py-2" 
      style="background: linear-gradient(135deg, #2c5364, #203a43, #0f2027); border: none; font-size: 1.1rem;"
      onclick="window.location.href='pagina1.php'">
      Volver
    </button>
    <!-- Botón Exportar a PDF -->
<!-- Botón Exportar a PDF -->
<form action="exportar_devoluciones_pdf.php" method="post">
  <button type="submit" class="btn btn-danger px-4 py-2" style="font-size: 1.1rem;">
    Exportar a PDF
  </button>
</form>


  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
