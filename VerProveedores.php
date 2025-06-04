<?php
require_once '../conexion.php';

try {
    $stmt = $pdo->query("SELECT * FROM Vista_Proveedores");
    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar la vista: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Proveedores</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }
    .icon-eye {
      cursor: pointer;
      font-size: 1.4rem;
      color: #0d6efd;
      transition: color 0.3s ease;
      text-decoration: none;
    }
    .icon-eye:hover {
      color: #0a58ca;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-5 text-uppercase fw-bold" style="font-size: 3rem; color: #fff; text-shadow: 2px 2px 6px rgba(0,0,0,0.7); letter-spacing: 2px;">
    ¡Proveedores Registrados!
  </h2>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Ver</th> <!-- Nueva columna para el ícono -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ($proveedores as $prov): ?>
          <tr>
            <td><?= htmlspecialchars($prov['idProveedor']) ?></td>
            <td><?= htmlspecialchars($prov['Nombre']) ?></td>
            <td>
              <a href="VerProductoProveedor.php?id=<?= urlencode($prov['idProveedor']) ?>" class="icon-eye" title="Ver proveedor">
                👁
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <br>

  <div class="d-flex justify-content-start gap-3 mt-4">
    <!-- Botón Salir -->
    <button type="button" class="btn text-white px-4 py-2" 
      style="background: linear-gradient(135deg, #2c5364, #203a43, #0f2027); border: none; font-size: 1.1rem;"
      onclick="window.location.href='pagina1.php'">
      Salir
    </button>

    <!-- Botón Registrar Nuevo Proveedor -->
    <a href="AgregarProvedor.php" class="btn text-white px-4 py-2"
      style="background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); border: none; font-size: 1.1rem;">
      Registrar Nuevo Proveedor
    </a>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
