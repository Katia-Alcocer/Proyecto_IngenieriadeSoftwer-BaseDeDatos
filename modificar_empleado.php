<?php
require_once '../conexion.php'; // Ajusta según ruta real
session_start();

$mensaje = '';
$idEmpleado = $_GET['id'] ?? null;

if (!$idEmpleado) {
    die("No se indicó el id del empleado.");
}

// Si es POST, procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idEmpleado = $_POST['idEmpleado'] ?? null;

    // Recoger datos
    $nombre = $_POST['nombre'] ?? '';
    $paterno = $_POST['paterno'] ?? '';
    $materno = $_POST['materno'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';
    $edad = $_POST['edad'] ?? 0;
    $sexo = $_POST['sexo'] ?? '';
    $calle = $_POST['calle'] ?? '';
    $numero = $_POST['numero'] ?? 0;
    $cp = $_POST['cp'] ?? 0;
    $idAsentamiento = $_POST['idAsentamiento'] ?? null;
    $rfc = $_POST['rfc'] ?? '';
    $curp = $_POST['curp'] ?? '';
    $nss = $_POST['nss'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $puesto = $_POST['puesto'] ?? '';

    $hashContrasena = $contrasena !== '' ? password_hash($contrasena, PASSWORD_DEFAULT) : '';

    try {
        $stmt = $pdo->prepare("EXEC ModificarEmpleado 
            @idEmpleado = ?, 
            @Nombre = ?, @Paterno = ?, @Materno = ?, @Telefono = ?, @Email = ?, @Edad = ?, @Sexo = ?, 
            @Calle = ?, @Numero = ?, @c_CP = ?, @idAsentamiento = ?, 
            @RFC = ?, @CURP = ?, @NumeroSeguro = ?, 
            @Usuario = ?, @Contrasena = ?, @Puesto = ?");

        $stmt->execute([
            $idEmpleado,
            $nombre, $paterno, $materno, $telefono, $email, $edad, $sexo,
            $calle, $numero, $cp, $idAsentamiento,
            $rfc, $curp, $nss,
            $usuario,
            $hashContrasena !== '' ? $hashContrasena : null,
            $puesto
        ]);

        $mensaje = "Empleado actualizado correctamente.";
    } catch (PDOException $e) {
        $mensaje = "Error al actualizar: " . $e->getMessage();
    }
}

// Obtener datos actuales
$stmt = $pdo->prepare("SELECT * FROM Vista_Empleados_Detallada WHERE idEmpleado = ?");
$stmt->execute([$idEmpleado]);
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$empleado) {
    die("Empleado no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Modificar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Modificar Empleado</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="idEmpleado" value="<?= htmlspecialchars($idEmpleado) ?>" />
        <input type="hidden" name="idAsentamiento" id="idAsentamiento" value="<?= htmlspecialchars($empleado['idAsentamiento'] ?? '') ?>">

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required value="<?= htmlspecialchars($empleado['Nombre'] ?? '') ?>" />
            </div>
            <div class="col-md-4 mb-3">
                <label for="paterno" class="form-label">Apellido Paterno</label>
                <input type="text" name="paterno" id="paterno" class="form-control" required value="<?= htmlspecialchars($empleado['Paterno'] ?? '') ?>" />
            </div>
            <div class="col-md-4 mb-3">
                <label for="materno" class="form-label">Apellido Materno</label>
                <input type="text" name="materno" id="materno" class="form-control" value="<?= htmlspecialchars($empleado['Materno'] ?? '') ?>" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($empleado['Telefono'] ?? '') ?>" />
            </div>
            <div class="col-md-4 mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($empleado['Email'] ?? '') ?>" />
            </div>
            <div class="col-md-4 mb-3">
                <label for="edad" class="form-label">Edad</label>
                <input type="number" name="edad" id="edad" class="form-control" min="0" max="150" value="<?= htmlspecialchars($empleado['Edad'] ?? '') ?>" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="sexo" class="form-label">Sexo</label>
                <select name="sexo" id="sexo" class="form-select">
                    <option value="" <?= ($empleado['Sexo'] ?? '') == '' ? 'selected' : '' ?>>Selecciona</option>
                    <option value="H" <?= ($empleado['Sexo'] ?? '') == 'H' ? 'selected' : '' ?>>Hombre</option>
                    <option value="M" <?= ($empleado['Sexo'] ?? '') == 'M' ? 'selected' : '' ?>>Mujer</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="calle" class="form-label">Calle</label>
                <input type="text" name="calle" id="calle" class="form-control" value="<?= htmlspecialchars($empleado['Calle'] ?? '') ?>" />
            </div>
            <div class="col-md-2 mb-3">
                <label for="numero" class="form-label">Número</label>
                <input type="number" name="numero" id="numero" class="form-control" value="<?= htmlspecialchars($empleado['Numero'] ?? '') ?>" />
            </div>
            <div class="col-md-2 mb-3">
                <label for="cp" class="form-label">Código Postal</label>
                <input type="number" name="cp" id="cp" class="form-control" value="<?= htmlspecialchars($empleado['c_CP'] ?? '') ?>" />
            </div>
        </div>

        <div class="mb-3">
            <label for="asentamiento" class="form-label">Asentamiento</label>
            <select name="asentamiento" id="asentamiento" class="form-select" required>
                <option value="">Selecciona un asentamiento</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="rfc" class="form-label">RFC</label>
                <input type="text" name="rfc" id="rfc" class="form-control" value="<?= htmlspecialchars($empleado['RFC'] ?? '') ?>" />
            </div>
            <div class="col-md-4 mb-3">
                <label for="curp" class="form-label">CURP</label>
                <input type="text" name="curp" id="curp" class="form-control" value="<?= htmlspecialchars($empleado['CURP'] ?? '') ?>" />
            </div>
            <div class="col-md-4 mb-3">
                <label for="nss" class="form-label">Número de Seguro Social</label>
                <input type="text" name="nss" id="nss" class="form-control" value="<?= htmlspecialchars($empleado['NumeroSeguroSocial'] ?? '') ?>" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-control" value="<?= htmlspecialchars($empleado['Usuario'] ?? '') ?>" />
            </div>
            <div class="col-md-4 mb-3">
                <label for="contrasena" class="form-label">Contraseña (deja vacío para no cambiar)</label>
                <input type="password" name="contrasena" id="contrasena" class="form-control" />
            </div>
            <div class="col-md-4 mb-3">
                <label for="puesto" class="form-label">Puesto</label>
                <input type="text" name="puesto" id="puesto" class="form-control" value="<?= htmlspecialchars($empleado['Puesto'] ?? '') ?>" />
            </div>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('cp').addEventListener('change', function () {
    const cp = this.value;
    if (!cp) return;

    fetch(`../api/consulta_cp.php?cp=${cp}`)
    

        .then(response => response.json())
        .then(data => {
            const asentamientoSelect = document.getElementById('asentamiento');
            asentamientoSelect.innerHTML = '<option value="">Selecciona un asentamiento</option>';

            data.forEach(asentamiento => {
                const option = document.createElement('option');
                option.value = asentamiento.idAsentamiento;
                option.textContent = `${asentamiento.Nombre} (${asentamiento.Municipio}, ${asentamiento.Estado})`;
                asentamientoSelect.appendChild(option);
            });

            // Marcar el asentamiento actual si ya estaba guardado
            const idAsentamientoPrevio = document.getElementById('idAsentamiento').value;
            if (idAsentamientoPrevio) {
                asentamientoSelect.value = idAsentamientoPrevio;
            }
        });
});

document.getElementById('asentamiento').addEventListener('change', function () {
    document.getElementById('idAsentamiento').value = this.value;
});
</script>
</body>
</html>
