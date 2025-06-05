<?php
require '../conexion.php';

$idEmpleado = $_GET['id'] ?? null;
if (!$idEmpleado) {
    die("ID de empleado no proporcionado.");
}

$stmt = $pdo->prepare("
    SELECT e.idEmpleado, e.RFC, e.CURP, e.NumeroSeguroSocial, e.Usuario, e.Contraseña, e.Puesto,
           p.Nombre, p.Paterno, p.Materno, p.Telefono, p.Email, p.Edad, p.Sexo,
           d.Calle, d.Numero, d.c_CP, d.idAsentamiento
    FROM Empleados e
    JOIN Personas p ON e.idPersona = p.idPersona
    JOIN Domicilios d ON p.idDomicilio = d.idDomicilio
    WHERE e.idEmpleado = ?
");
$stmt->execute([$idEmpleado]);
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$empleado) {
    die("Empleado no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container mt-4">
    <h2>Modificar Empleado</h2>
    <form method="POST" action="procesar_modificacion.php">
        <input type="hidden" name="idEmpleado" value="<?= $empleado['idEmpleado'] ?>">

        <div class="row mb-3">
            <div class="col-md-4"><label>Nombre</label><input type="text" name="Nombre" value="<?= $empleado['Nombre'] ?>" class="form-control" required></div>
            <div class="col-md-4"><label>Paterno</label><input type="text" name="Paterno" value="<?= $empleado['Paterno'] ?>" class="form-control" required></div>
            <div class="col-md-4"><label>Materno</label><input type="text" name="Materno" value="<?= $empleado['Materno'] ?>" class="form-control"></div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3"><label>Teléfono</label><input type="text" name="Telefono" value="<?= $empleado['Telefono'] ?>" class="form-control"></div>
            <div class="col-md-3"><label>Email</label><input type="email" name="Email" value="<?= $empleado['Email'] ?>" class="form-control"></div>
            <div class="col-md-3"><label>Edad</label><input type="number" name="Edad" value="<?= $empleado['Edad'] ?>" class="form-control"></div>
            <div class="col-md-3">
                <label>Sexo</label>
                <select name="Sexo" class="form-select">
                    <option value="M" <?= $empleado['Sexo'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                    <option value="F" <?= $empleado['Sexo'] == 'F' ? 'selected' : '' ?>>Femenino</option>
                </select>
            </div>
        </div>

        <h5>Domicilio</h5>
        <div class="row mb-3">
            <div class="col-md-4"><label>Calle</label><input type="text" name="Calle" value="<?= $empleado['Calle'] ?>" class="form-control" required></div>
            <div class="col-md-2"><label>Número</label><input type="number" name="Numero" value="<?= $empleado['Numero'] ?>" class="form-control" required></div>
            <div class="col-md-3"><label>Código Postal</label><input type="text" id="codigoPostal" value="<?= $empleado['c_CP'] ?>" class="form-control" required></div>
            <div class="col-md-3">
                <label>Asentamiento</label>
                <select name="idAsentamiento" id="asentamientoSelect" class="form-select" required>
                    <option value="">Cargando...</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4"><label>Estado</label><input type="text" id="estado" class="form-control" disabled></div>
            <div class="col-md-4"><label>Municipio</label><input type="text" id="municipio" class="form-control" disabled></div>
            <div class="col-md-4"><label>País</label><input type="text" id="pais" class="form-control" disabled></div>
        </div>

        <input type="hidden" name="c_CP" id="c_CP" value="<?= htmlspecialchars($empleado['c_CP']) ?>">

        <h5>Datos Laborales</h5>
        <div class="row mb-3">
            <div class="col-md-3"><label>RFC</label><input type="text" name="RFC" value="<?= $empleado['RFC'] ?>" class="form-control" required></div>
            <div class="col-md-3"><label>CURP</label><input type="text" name="CURP" value="<?= $empleado['CURP'] ?>" class="form-control" required></div>
            <div class="col-md-3"><label>Seguro Social</label><input type="text" name="NumeroSeguro" value="<?= $empleado['NumeroSeguroSocial'] ?>" class="form-control"></div>
            <div class="col-md-3"><label>Puesto</label><input type="text" name="Puesto" value="<?= $empleado['Puesto'] ?>" class="form-control"></div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6"><label>Usuario</label><input type="text" name="Usuario" value="<?= $empleado['Usuario'] ?>" class="form-control" required></div>
            <div class="col-md-6"><label>Contraseña</label><input type="password" name="Contrasena" value="<?= $empleado['Contraseña'] ?>" class="form-control" required></div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const cpInput = document.getElementById('codigoPostal');
        const asentamientoSelect = document.getElementById('asentamientoSelect');
        const estadoInput = document.getElementById('estado');
        const municipioInput = document.getElementById('municipio');
        const paisInput = document.getElementById('pais');
        const hiddenCP = document.getElementById('c_CP');

        function actualizarCamposGeo(option) {
            estadoInput.value = option.dataset.estado;
            municipioInput.value = option.dataset.municipio;
            paisInput.value = option.dataset.pais;
            hiddenCP.value = option.dataset.ccp;
        }

        function cargarAsentamientos(cp, selectedId = null) {
            fetch('consulta_cp.php?cp=' + encodeURIComponent(cp))
                .then(res => res.json())
                .then(data => {
                    asentamientoSelect.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(a => {
                            const option = document.createElement('option');
                            option.value = a.idAsentamiento;
                            option.textContent = a.Asentamiento;
                            option.dataset.estado = a.Estado;
                            option.dataset.municipio = a.Municipio;
                            option.dataset.pais = a.Pais;
                            option.dataset.ccp = a.c_CP;

                            if (selectedId && parseInt(a.idAsentamiento) === parseInt(selectedId)) {
                                option.selected = true;
                                actualizarCamposGeo(option);
                            }

                            asentamientoSelect.appendChild(option);
                        });

                        asentamientoSelect.addEventListener('change', function () {
                            const selected = asentamientoSelect.options[asentamientoSelect.selectedIndex];
                            actualizarCamposGeo(selected);
                        });
                    } else {
                        asentamientoSelect.innerHTML = '<option value="">Sin asentamientos</option>';
                        estadoInput.value = '';
                        municipioInput.value = '';
                        paisInput.value = '';
                        hiddenCP.value = '';
                    }
                });
        }

        if (cpInput.value) {
            cargarAsentamientos(cpInput.value, <?= json_encode($empleado['idAsentamiento']) ?>);
        }

        cpInput.addEventListener('blur', function () {
            if (this.value) {
                cargarAsentamientos(this.value);
            }
        });
    });
    </script>
</body>
</html>
