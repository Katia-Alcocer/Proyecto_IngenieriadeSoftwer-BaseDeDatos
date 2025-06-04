<?php
require_once '../conexion.php';

try {
    $stmt = $pdo->query("SELECT * FROM vw_ClientesActivos");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar la vista: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Clientes Activos</title>
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
    ¡Clientes Activos Registrados!
  </h2>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Apellidos</th>
          <th>Teléfono</th>
          <th>Email</th>
          <th>Edad</th>
          <th>Sexo</th>
          <th>Calle</th>
          <th>Número</th>
          <th>Código Postal</th>
          <th>Asentamiento</th>
          <th>Tipo</th>
          <th>Zona</th>
          <th>Municipio</th>
          <th>Ciudad</th>
          <th>Estado</th>
          <th>País</th>
          <th>Crédito</th>
          <th>Límite</th>
          <th>Descuento</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clientes as $cli): ?>
          <tr>
            <td><?= htmlspecialchars($cli['idCliente']) ?></td>
            <td><?= htmlspecialchars($cli['Nombre']) ?></td>
            <td><?= htmlspecialchars($cli['Paterno'] . ' ' . $cli['Materno']) ?></td>
            <td><?= htmlspecialchars($cli['Telefono']) ?></td>
            <td><?= htmlspecialchars($cli['Email']) ?></td>
            <td><?= htmlspecialchars($cli['Edad']) ?></td>
            <td><?= htmlspecialchars($cli['Sexo']) ?></td>
            <td><?= htmlspecialchars($cli['Calle']) ?></td>
            <td><?= htmlspecialchars($cli['Numero']) ?></td>
            <td><?= htmlspecialchars($cli['CodigoPostal']) ?></td>
            <td><?= htmlspecialchars($cli['Asentamiento']) ?></td>
            <td><?= htmlspecialchars($cli['TipoAsentamiento']) ?></td>
            <td><?= htmlspecialchars($cli['Zona']) ?></td>
            <td><?= htmlspecialchars($cli['Municipio']) ?></td>
            <td><?= htmlspecialchars($cli['Ciudad']) ?></td>
            <td><?= htmlspecialchars($cli['Estado']) ?></td>
            <td><?= htmlspecialchars($cli['Pais']) ?></td>
            <td><?= htmlspecialchars($cli['Credito']) ?></td>
            <td><?= htmlspecialchars($cli['Limite']) ?></td>
            <td><?= htmlspecialchars($cli['Descuento']) ?></td>
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

    <!-- Botón Registrar Nuevo Cliente -->
    <a href="AgregarCliente.php" class="btn text-white px-4 py-2"
      style="background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); border: none; font-size: 1.1rem;">
      Registrar Nuevo Cliente
    </a>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
