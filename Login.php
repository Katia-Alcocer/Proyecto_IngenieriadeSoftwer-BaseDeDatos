<?php
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    try {
        $stmt = $pdo->prepare("EXEC ValidarInicioSesion ?, ?, ?");
        $pagina = null;
        $stmt->bindParam(1, $usuario);
        $stmt->bindParam(2, $contrasena);
        $stmt->bindParam(3, $pagina, PDO::PARAM_INPUT_OUTPUT, 255); // Salida

        $stmt->execute();

        if ($pagina) {
            header("Location: $pagina");
            exit;
        } else {
            echo "<script>alert('Usuario o contraseña incorrectos');</script>";
        }
    } catch (PDOException $e) {
        die("Error ejecutando el procedimiento: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <!-- Bootstrap 5 CSS CDN -->
<!-- MDBootstrap CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />
<!-- Bootstrap 5 CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="styleLogin.css">

<!-- MDBootstrap JS -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>


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

                <form method="POST" action="">
                  <p>Por favor, inicia sesión en tu cuenta</p>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="email"  name="usuario" id="form2Example11" class="form-control"
                      placeholder="Correo Electronico" required/>
                    <label class="form-label" for="form2Example11">Usuario:</label>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" name="contrasena" id="form2Example22" class="form-control" placeholder="Contraseña" required/>
                    <label class="form-label" for="form2Example22">Contraseña:</label>
                  </div>

                  <div class="text-center pt-1 mb-5 pb-1">
                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="button">Iniciar Sesión</button>
                    <a class="text-muted" href="#!">Olvidó su Contraseña?</a>
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

   <!-- Bootstrap 5 JS + Popper.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
