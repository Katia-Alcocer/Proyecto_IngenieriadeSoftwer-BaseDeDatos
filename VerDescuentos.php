<?php
require_once '../conexion.php';

try {
    $stmt = $pdo->query("SELECT * FROM vw_DescuentosDisponibles");
    $descuentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar la vista: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Descuentos Disponibles</title>
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
    ¡Descuentos Disponibles!
  </h2>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Categoría</th>
          <th>Porcentaje</th>
          <th>Texto</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($descuentos as $desc): ?>
          <tr>
            <td><?= htmlspecialchars($desc['ID']) ?></td>
            <td><?= htmlspecialchars($desc['CategoriaDescuento']) ?></td>
            <td><?= htmlspecialchars($desc['PorcentajeDecimal']) ?></td>
            <td><?= htmlspecialchars($desc['PorcentajeTexto']) ?></td>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
