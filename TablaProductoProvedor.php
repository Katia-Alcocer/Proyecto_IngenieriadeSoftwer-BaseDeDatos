<?php
require_once '../conexion.php';

$proveedores = [];
$productos = [];
$mensaje = "";
$mensajeTipo = "";

try {
  
    $stmtProv = $pdo->query("SELECT idProveedor, Nombre FROM Vista_Proveedores ORDER BY Nombre");
    $proveedores = $stmtProv->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idProveedor'])) {
        $idProveedor = intval($_POST['idProveedor']);
        if ($idProveedor > 0) {
            // Llamar al procedimiento almacenado para obtener productos del proveedor seleccionado
            $stmtProd = $pdo->prepare("EXEC ObtenerProductosPorProveedor @p_idProveedor = :idProveedor");
            $stmtProd->bindParam(':idProveedor', $idProveedor, PDO::PARAM_INT);
            $stmtProd->execute();
            $productos = $stmtProd->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $mensaje = "Selecciona un proveedor válido.";
            $mensajeTipo = "warning";
        }
    }
} catch (PDOException $e) {
    $mensaje = "Error de base de datos: " . $e->getMessage();
    $mensajeTipo = "danger";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Productos por Proveedor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      color: #fff;
    }
    .container {
      background-color: rgba(0,0,0,0.6);
      padding: 30px;
      border-radius: 8px;
      margin-top: 40px;
    }
    table {
      background-color: #fff;
      color: #000;
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="mb-4 text-uppercase fw-bold" style="letter-spacing: 2px;">Productos por Proveedor</h2>

  <?php if ($mensaje): ?>
    <div class="alert alert-<?php echo htmlspecialchars($mensajeTipo); ?>" role="alert">
      <?php echo htmlspecialchars($mensaje); ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="mb-4">
    <label for="idProveedor" class="form-label">Selecciona un Proveedor:</label>
    <select name="idProveedor" id="idProveedor" class="form-select" required>
      <option value="">-- Seleccionar proveedor --</option>
      <?php foreach ($proveedores as $prov): ?>
        <option value="<?= htmlspecialchars($prov['idProveedor']) ?>"
          <?= (isset($idProveedor) && $idProveedor == $prov['idProveedor']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($prov['Nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-primary mt-3">Mostrar Productos</button>
  </form>

  <?php if (!empty($productos)): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="table-dark">
          <tr>
            <th>ID Producto</th>
            <th>Nombre</th>
            <th>Precio Compra</th>
            <th>Precio Venta</th>
            <th>Stock</th>
            <th>Estado</th>
            <th>ID Categoría</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($productos as $prod): ?>
            <tr>
              <td><?= htmlspecialchars($prod['idProducto']) ?></td>
              <td><?= htmlspecialchars($prod['Nombre']) ?></td>
              <td>$<?= number_format($prod['PrecioCompra'], 2) ?></td>
              <td>$<?= number_format($prod['PrecioVenta'], 2) ?></td>
              <td><?= htmlspecialchars($prod['Stock']) ?></td>
              <td><?= htmlspecialchars($prod['Estado']) ?></td>
              <td><?= htmlspecialchars($prod['idCategoria']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php elseif (isset($idProveedor)): ?>
    <div class="alert alert-info">No se encontraron productos para este proveedor.</div>
  <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>