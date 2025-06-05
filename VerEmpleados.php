<?php
require_once '../conexion.php';

try {
    $stmt = $pdo->query("SELECT * FROM Vista_Empleados_Detallada");
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar la vista: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Empleados</title>
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
  ¡Empleados Registrados!
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
    <th>RFC</th>
    <th>CURP</th>
    <th>NSS</th>
    <th>Usuario</th>
    <th>Puesto</th>
    <th>Editar</th> 
  </tr>
</thead>
<tbody>
  <?php foreach ($empleados as $emp): ?>
    <tr>
      <td><?= htmlspecialchars($emp['idEmpleado']) ?></td>
      <td><?= htmlspecialchars($emp['Nombre']) ?></td>
      <td><?= htmlspecialchars($emp['Paterno'] . ' ' . $emp['Materno']) ?></td>
      <td><?= htmlspecialchars($emp['Telefono']) ?></td>
      <td><?= htmlspecialchars($emp['Email']) ?></td>
      <td><?= htmlspecialchars($emp['Edad']) ?></td>
      <td><?= htmlspecialchars($emp['Sexo']) ?></td>
      <td><?= htmlspecialchars($emp['Calle']) ?></td>
      <td><?= htmlspecialchars($emp['Numero']) ?></td>
      <td><?= htmlspecialchars($emp['CodigoPostal']) ?></td>
      <td><?= htmlspecialchars($emp['Asentamiento']) ?></td>
      <td><?= htmlspecialchars($emp['TipoAsentamiento']) ?></td>
      <td><?= htmlspecialchars($emp['Zona']) ?></td>
      <td><?= htmlspecialchars($emp['Municipio']) ?></td>
      <td><?= htmlspecialchars($emp['Ciudad']) ?></td>
      <td><?= htmlspecialchars($emp['Estado']) ?></td>
      <td><?= htmlspecialchars($emp['Pais']) ?></td>
      <td><?= htmlspecialchars($emp['RFC']) ?></td>
      <td><?= htmlspecialchars($emp['CURP']) ?></td>
      <td><?= htmlspecialchars($emp['NumeroSeguroSocial']) ?></td>
      <td><?= htmlspecialchars($emp['Usuario']) ?></td>
      <td><?= htmlspecialchars($emp['Puesto']) ?></td>

      
      <td class="text-center align-middle">
        <a href="modificar_empleado.php?id=<?= urlencode($emp['idEmpleado']) ?>" 
           class="btn btn-sm btn-primary" 
           title="Editar empleado">
         
          <svg xmlns="http://www.w3.org/2000/svg" 
               width="16" height="16" fill="currentColor" 
               class="bi bi-pencil" viewBox="0 0 16 16">
            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 
                     .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 
                     .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zM10.5 
                     3.207 2 11.707V13h1.293l8.5-8.5-1.293-1.293z"/>
          </svg>
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

  <!-- Botón Registrar Nuevo Empleado -->
  <a href="AgregarEmpleado.php" class="btn text-white px-4 py-2"
    style="background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); border: none; font-size: 1.1rem;">
    Registrar Nuevo Empleado
  </a>
</div>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>