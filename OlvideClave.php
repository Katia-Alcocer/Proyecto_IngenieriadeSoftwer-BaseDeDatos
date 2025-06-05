<?php
session_start();
require_once '../conexion.php'; // Debe definir $pdo
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['verificar'])) {
    $nombre = trim($_POST['Nombre']);
    $correo = trim($_POST['Correo']);
    $puesto = trim($_POST['Puesto']);
    $telefono = trim($_POST['Telefono']);

    $sql = "SELECT E.idEmpleado 
            FROM Empleados E
            JOIN Personas P ON E.idPersona = P.idPersona
            WHERE P.Nombre = ? AND P.Email = ? AND E.Puesto = ? AND P.Telefono = ?";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $correo, $puesto, $telefono]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $_SESSION['usuario_recuperar'] = $correo;
            $_SESSION['id'] = $row['idEmpleado'];
            header("Location: NuevaClave.php");
            exit();
        } else {
            $error = "Los datos no coinciden con ningún empleado.";
        }
    } catch (PDOException $e) {
        $error = "Error en la consulta: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Clave</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleLogin.css"> <!-- Puedes adaptar aquí tu estilo -->
</head>
<body>
<section class="h-100 gradient-form" style="background-color: #f5f5f5;">
    <div class="container py-5 h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-2-strong">
                    <div class="card-body p-5">

                        <div class="text-center mb-4">
                            <h4 class="mb-3">Recuperar Clave</h4>
                            <a href="Login.php" class="btn btn-outline-secondary btn-sm">← Volver</a>
                        </div>

                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-outline mb-3">
                                <input type="text" name="Nombre" id="nombre" class="form-control" required />
                                <label class="form-label" for="nombre">Nombre</label>
                            </div>

                            <div class="form-outline mb-3">
                                <input type="email" name="Correo" id="correo" class="form-control" required />
                                <label class="form-label" for="correo">Correo electrónico</label>
                            </div>

                            <div class="form-outline mb-3">
                                <input type="text" name="Puesto" id="puesto" class="form-control" required />
                                <label class="form-label" for="puesto">Puesto</label>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="text" name="Telefono" id="telefono" class="form-control" required />
                                <label class="form-label" for="telefono">Teléfono</label>
                            </div>

                            <button class="btn btn-primary btn-block" name="verificar" type="submit">Verificar</button>
                        </form>
                    </div>
                </div>
                <footer class="text-center mt-3">
                    <small class="text-muted">&copy; 2025 Diamonds Corporation. Todos los derechos reservados.</small>
                </footer>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</body>
</html>
