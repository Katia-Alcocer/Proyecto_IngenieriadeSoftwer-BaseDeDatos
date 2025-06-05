<?php
require_once '../conexion.php';

try {
    $stmt = $pdo->query("SELECT TOP 1000 * FROM VistaPedidosEnviados");
    $pedidosEnviados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar la vista: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pedidos Enviados</title>
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
    Pedidos Enviados
  </h2>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID Pedido</th>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Estatus</th>
          <th>ID Cliente</th>
          <th>Nombre Cliente</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pedidosEnviados as $pedido): ?>
          <tr>
            <td><?= htmlspecialchars($pedido['idPedido']) ?></td>
            <td><?= htmlspecialchars($pedido['Fecha']) ?></td>
            <td><?= htmlspecialchars($pedido['Hora']) ?></td>
            <td><?= htmlspecialchars($pedido['Estatus']) ?></td>
            <td><?= htmlspecialchars($pedido['idCliente']) ?></td>
            <td><?= htmlspecialchars($pedido['NombreCliente']) ?></td>
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

   
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
