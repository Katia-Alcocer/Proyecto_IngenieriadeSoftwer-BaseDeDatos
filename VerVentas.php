<?php
session_start();

// Conexión a la base de datos (PDO)
require_once '../conexion.php'; // Asegúrate de que esta línea incluya el archivo con $pdo

// Establecer variable de sesión para el empleado (si aplica)
//$empleado_id = $_SESSION['id'] ?? 1;
//$pdo->query("SET @empleado_id = $empleado_id");

// Fecha seleccionada
$fechaSeleccionada = date('Y-m-d');

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["fecha"])) {
        $fechaSeleccionada = $_POST["fecha"];
    }

    if (isset($_POST["accion"])) {
        $accion = $_POST["accion"];

        if ($accion === "descargar") {
            header("Location: TicketVentasDiarias.php?fecha=" . urlencode($fechaSeleccionada));
            exit;
        }
    }
}

// Consultar las ventas del día desde la vista
$ventas = [];
$sql = "SELECT * FROM VistaVentasDiarias WHERE CAST(Fecha AS DATE) = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $fechaSeleccionada);
$stmt->execute();
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Resumen de Ventas del Día</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/jpg" href="Imagenes/Logo.jpg">
  <style>
    body {
      background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    h1, h2 {
      color: white;
      text-shadow: 2px 2px 6px rgba(0,0,0,0.7);
    }

    .formulario label, .formulario input, .formulario button {
      color: white;
    }

    .formulario {
      background: rgba(0, 0, 0, 0.5);
      padding: 1.5rem;
      border-radius: 10px;
      margin-bottom: 2rem;
    }

    footer {
      color: white;
      text-align: center;
      padding: 1rem;
    }
  </style>
</head>
<body>
  <div class="container mt-5">

    <h1 class="text-center mb-4"> Ventas del Día</h1>

    <div class="formulario">
      <form method="POST" action="" class="row g-3">
        <div class="col-auto">
          <label for="fecha" class="form-label">Fecha:</label>
          <input type="date" name="fecha" id="fecha" class="form-control" required value="<?php echo htmlspecialchars($fechaSeleccionada); ?>">
        </div>
        <div class="col-auto align-self-end">
          <button type="submit" name="accion" value="buscar" class="btn btn-primary">Buscar</button>
          <button type="submit" name="accion" value="descargar" class="btn btn-success">Descargar</button>
        </div>
      </form>
    </div>

    <?php if (empty($ventas)): ?>
      <div class="alert alert-warning text-center fw-bold">No hay ventas registradas en esta fecha.</div>
    <?php else: ?>
      <?php 
        $ventasPorEmpleado = [];
        foreach ($ventas as $venta) {
            $ventasPorEmpleado[$venta['Empleado']][] = $venta;
        }

        foreach ($ventasPorEmpleado as $empleado => $ventasEmpleado): 
      ?>
        <h2 class="mb-3">Ventas de <?php echo htmlspecialchars($empleado); ?></h2>
        <div class="table-responsive mb-5">
          <table class="table table-bordered table-striped table-hover bg-white">
            <thead class="table-dark">
              <tr>
                <th>Número de Venta</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
                <th>Hora</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $sumaTotalEmpleado = 0;
              foreach ($ventasEmpleado as $venta): 
                  $sumaTotalEmpleado += $venta['Subtotal'];
              ?>
              <tr>
                <td><?php echo $venta['NumeroVenta']; ?></td>
                <td><?php echo $venta['Cliente']; ?></td>
                <td><?php echo $venta['Producto']; ?></td>
                <td><?php echo number_format($venta['Cantidad'], 2); ?></td>
                <td><?php echo '$' . number_format($venta['PrecioUnitario'], 2); ?></td>
                <td><?php echo '$' . number_format($venta['Subtotal'], 2); ?></td>
                <td><?php echo $venta['Hora']; ?></td>
              </tr>
              <?php endforeach; ?>
              <tr class="table-secondary">
                <td colspan="5" class="text-end"><strong>Total del día:</strong></td>
                <td colspan="2"><strong><?php echo '$' . number_format($sumaTotalEmpleado, 2); ?></strong></td>
              </tr>
            </tbody>
          </table>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

   
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
