<?php require_once '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $c_CP = intval(trim($_POST["c_CP"])); 

        $stmt = $pdo->prepare("EXEC RegistrarCliente 
            @Calle = ?, @Numero = ?, @c_CP = ?, 
            @Nombre = ?, @Paterno = ?, @Materno = ?, 
            @Telefono = ?, @Email = ?, @Edad = ?, @Sexo = ?, 
            @Credito = ?, @Limite = ?, @idDescuento = ?");

        $stmt->execute([
            $_POST["Calle"],
            $_POST["Numero"],
            $c_CP,
            $_POST["Nombre"],
            $_POST["Paterno"],
            $_POST["Materno"],
            $_POST["Telefono"],
            $_POST["Email"],
            $_POST["Edad"],
            $_POST["Sexo"],
            $_POST["Credito"],
            $_POST["Limite"],
            $_POST["idDescuento"]
        ]);

        echo "<script>
                alert('Cliente registrado con éxito.');
                window.location.href = 'VerClientes.php';
              </script>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Cliente</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</head>
<body>
<section class="text-center">
  <div class="p-5 bg-image" style="background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg'); height: 300px;"></div>
  <div class="card mx-4 mx-md-5 shadow-5-strong bg-body-tertiary" style="margin-top: -100px; backdrop-filter: blur(30px);">
    <div class="card-body py-5 px-md-5">
      <div class="row d-flex justify-content-center">
        <div class="col-lg-10">
          <h2 class="fw-bold mb-5">Registrar Cliente</h2>
          <form method="POST" action="VerClientes.php">
            <div class="row">
              <div class="col-md-4 mb-4"><input type="text" name="Nombre" class="form-control" required><label class="form-label">Nombre</label></div>
              <div class="col-md-4 mb-4"><input type="text" name="Paterno" class="form-control" required><label class="form-label">Apellido Paterno</label></div>
              <div class="col-md-4 mb-4"><input type="text" name="Materno" class="form-control" required><label class="form-label">Apellido Materno</label></div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-4"><input type="text" name="Telefono" class="form-control" required><label class="form-label">Teléfono</label></div>
              <div class="col-md-4 mb-4"><input type="email" name="Email" class="form-control" required><label class="form-label">Email</label></div>
              <div class="col-md-4 mb-4"><input type="number" name="Edad" class="form-control" required><label class="form-label">Edad</label></div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-4">
                <select name="Sexo" class="form-select" required>
                 <option value="H">Hombre</option>
                  <option value="M">Mujer</option>
                </select>
                <label class="form-label">Sexo</label>
              </div>
              <div class="col-md-4 mb-4"><input type="text" name="Calle" class="form-control" required><label class="form-label">Calle</label></div>
              <div class="col-md-4 mb-4"><input type="number" name="Numero" class="form-control" required><label class="form-label">Número</label></div>
            </div>

           
            <div class="row">
              <div class="col-md-6 mb-4">
                <label for="codigoPostal" class="form-label">Código Postal</label>
                <input type="text" class="form-control" id="codigoPostal" name="codigoPostal" required>
              </div>
              <div class="col-md-6 mb-4">
                <label for="asentamiento" class="form-label">Asentamiento</label>
                <select class="form-select" id="asentamiento" name="idAsentamiento" required></select>
              </div>
            </div>

            
            <input type="hidden" name="c_CP" id="c_CP" />

           
            <div class="row">
              <div class="col-md-4 mb-4"><label for="estado" class="form-label">Estado</label><input type="text" class="form-control" id="estado" disabled></div>
              <div class="col-md-4 mb-4"><label for="municipio" class="form-label">Municipio</label><input type="text" class="form-control" id="municipio" disabled></div>
              <div class="col-md-4 mb-4"><label for="pais" class="form-label">País</label><input type="text" class="form-control" id="pais" disabled></div>
            </div>

           
            <div class="row">
              <div class="row">
                    <div class="col-md-4 mb-4">
                        <label class="form-label">Crédito</label>
                        <input type="number" step="0.01" name="Credito" id="Credito" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label">Límite</label>
                        <input type="number" step="0.01" name="Limite" id="Limite" class="form-control" required readonly>
                    </div>
                    

                    <script>
                    document.getElementById('Credito').addEventListener('input', function () {
                    document.getElementById('Limite').value = this.value;
                    });
                    </script>

              <div class="col-md-4 mb-4">
                <label for="idDescuento" class="form-label">Descuento</label>
                <select name="idDescuento" class="form-select" required>
                  <option value="1">Sin descuento</option>
                  <option value="2">Cliente frecuente</option>
                  <option value="3">Promoción</option>
                 
                </select>
              </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block mb-4">Registrar</button>
            <button type="button" class="btn btn-secondary btn-block mb-4" onclick="window.location.href='VerClientes.php'">Salir</button>

          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('codigoPostal').addEventListener('blur', function() {
    let cp = this.value;
    if (cp.length === 5) {
      fetch('consulta_cp.php?cp=' + cp)
        .then(response => response.json())
        .then(data => {
          const asentamientos = document.getElementById('asentamiento');
          asentamientos.innerHTML = '';
          if (data.length > 0) {
            data.forEach(item => {
              let option = document.createElement('option');
              option.value = item.idAsentamiento;
              option.text = item.Asentamiento;
              option.dataset.estado = item.Estado;
              option.dataset.municipio = item.Municipio;
              option.dataset.pais = item.Pais;
              option.dataset.ccp = item.c_CP;
              asentamientos.appendChild(option);
            });

           
            let first = asentamientos.options[0];
            document.getElementById('estado').value = first.dataset.estado;
            document.getElementById('municipio').value = first.dataset.municipio;
            document.getElementById('pais').value = first.dataset.pais;
            document.getElementById('c_CP').value = first.dataset.ccp;

            asentamientos.addEventListener('change', function() {
              let selected = asentamientos.options[asentamientos.selectedIndex];
              document.getElementById('estado').value = selected.dataset.estado;
              document.getElementById('municipio').value = selected.dataset.municipio;
              document.getElementById('pais').value = selected.dataset.pais;
              document.getElementById('c_CP').value = selected.dataset.ccp;
            });
          } else {
            document.getElementById('estado').value = '';
            document.getElementById('municipio').value = '';
            document.getElementById('pais').value = '';
            document.getElementById('c_CP').value = '';
          }
        });
    }
  });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>