<?php
require_once '../conexion.php';

try {
    $stmt = $pdo->query("SELECT * FROM Vista_ProductosNoAptos");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar la vista: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos No Aptos</title>
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
    ¡Productos No Aptos para Venta!
  </h2>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID Producto</th>
          <th>Nombre</th>
          <th>Estado</th>
          <th>ID Empleado</th>
          <th>Cantidad</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($productos as $prod): ?>
          <tr>
            <td><?= htmlspecialchars($prod['idProducto']) ?></td>
            <td><?= htmlspecialchars($prod['Nombre']) ?></td>
            <td><?= htmlspecialchars($prod['Estado']) ?></td>
            <td><?= htmlspecialchars($prod['idEmpleado']) ?></td>
            <td><?= htmlspecialchars($prod['Cantidad']) ?></td>
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
      Salir
    </button>

    
    <a href="RegistrarVenta.php" class="btn text-white px-4 py-2"
      style="background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); border: none; font-size: 1.1rem;">
      Ir a Ventas
    </a>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
