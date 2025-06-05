<?php
require_once '../conexion.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mensaje = "";
$mensajeTipo = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $nombreProveedor = trim($_POST["Nombre"]);

        if (empty($nombreProveedor)) {
            throw new Exception("El nombre del proveedor está vacío.");
        }

        // Llamada al procedimiento almacenado con parámetros nombrados
        $stmt = $pdo->prepare("EXEC AgregarProveedor @p_nombre = :nombre");
        $stmt->bindParam(':nombre', $nombreProveedor, PDO::PARAM_STR);
        $stmt->execute();

        $mensaje = "Proveedor registrado con éxito.";
        $mensajeTipo = "success";
    } catch (PDOException $e) {
        $mensaje = "Error de base de datos: " . $e->getMessage();
        $mensajeTipo = "danger";
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
        $mensajeTipo = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Proveedor</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<section class="text-center">
  <div class="p-5 bg-image" style="background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg'); height: 300px;"></div>
  <div class="card mx-4 mx-md-5 shadow-5-strong bg-body-tertiary" style="margin-top: -100px; backdrop-filter: blur(30px);">
    <div class="card-body py-5 px-md-5">
      <div class="row d-flex justify-content-center">
        <div class="col-lg-8">
          <h2 class="fw-bold mb-5">Registrar Proveedor</h2>

          <?php if (!empty($mensaje)): ?>
            <div class="alert alert-<?php echo $mensajeTipo; ?> alert-dismissible fade show" role="alert">
              <?php echo htmlspecialchars($mensaje); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="form-outline mb-4">
              <input type="text" name="Nombre" class="form-control" required />
              <label class="form-label">Nombre del Proveedor</label>
            </div>

            <button type="submit" class="btn btn-primary btn-block mb-4">Registrar</button>
            <a href="VerProveedores.php" class="btn btn-secondary btn-block mb-4">Salir</a>
          </form>

        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</body>
</html>
