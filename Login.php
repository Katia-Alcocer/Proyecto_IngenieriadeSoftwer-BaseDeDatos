<?php
require_once '../conexion.php'; // Ajusta la ruta según sea necesario
session_start();

$usuarioInput = $_POST['usuario'] ?? '';
$claveInput = $_POST['contrasena'] ?? '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM Empleados WHERE Usuario = ?");
    $stmt->execute([$usuarioInput]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $hashAlmacenado = $usuario['Contraseña'];

        if (password_verify($claveInput, $hashAlmacenado)) {
            iniciarSesion($usuario);
        } elseif (hash("sha256", $claveInput) === $hashAlmacenado) {
            $nuevoHash = password_hash($claveInput, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE Empleados SET Contraseña = ? WHERE idEmpleado = ?");
            $update->execute([$nuevoHash, $usuario['idEmpleado']]);
            iniciarSesion($usuario);
        } else {
            $mensaje = "Contraseña incorrecta.";
        }
    } else {
        $mensaje = "Usuario no encontrado.";
    }
}

function iniciarSesion($usuario) {
    $_SESSION['idEmpleado'] = $usuario['idEmpleado'];
    $_SESSION['Usuario'] = $usuario['Usuario'];
    header("Location: dashboard.php");
    exit();
}
?>

function iniciarSesion($usuario) {
    $_SESSION['idEmpleado'] = $usuario['idEmpleado'];
    $_SESSION['Usuario'] = $usuario['Usuario'];
    header("Location: dashboard.php"); // Redirige a la página de inicio
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleLogin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</head>
<body>
<section class="h-100 gradient-form" style="background-color: #eee;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-xl-10">
        <div class="card rounded-3 text-black">
          <div class="row g-0">
            <div class="col-lg-6">
              <div class="card-body p-md-5 mx-md-4">

                <div class="text-center">
                  <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/lotus.webp"
                    style="width: 185px;" alt="logo">
                  <h4 class="mt-1 mb-5 pb-1">Bienvenido a tu Plataforma de Trabajo</h4>
                </div>

                <?php if (!empty($mensaje)) : ?>
                  <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
                <?php endif; ?>

                <form method="POST" action="pagina1.php">
                  <p>Por favor, inicia sesión en tu cuenta</p>

                  <div class="form-outline mb-4">
                    <input type="email" name="usuario" id="form2Example11" class="form-control"
                      placeholder="Correo Electrónico" required/>
                    <label class="form-label" for="form2Example11">Usuario:</label>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" name="contrasena" id="form2Example22" class="form-control" placeholder="Contraseña" required/>
                    <label class="form-label" for="form2Example22">Contraseña:</label>
                  </div>

                  <div class="text-center pt-1 mb-5 pb-1">
                    <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Iniciar Sesión</button>
                  </div>

                </form>

              </div>
            </div>
            <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
              <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                <h4 class="mb-4">Somos más que una herrería</h4>
                <p class="small mb-0">Somos un aliado en la venta de materiales metálicos y productos para herrería. 
                    Ofrecemos tubos, ángulos, láminas, perfiles y todo lo que nuestros clientes necesitan para sus proyectos. 
                    Nos enfocamos en brindar atención rápida, productos de calidad y un servicio que marca la diferencia.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
