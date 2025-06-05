<?php
// Incluye tu archivo de conexión si está separado
require '../conexion.php'; 

// Ya tienes $pdo disponible aquí

try {
    $stmt = $pdo->query("SELECT idProducto, Nombre, PrecioVenta FROM Productos"); // Ajusta el nombre de tabla y campos según tu BD
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener productos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Registrar Venta</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
  <style>
    .remove-btn {
      cursor: pointer;
      color: red;
      font-weight: bold;
      font-size: 1.4rem;
    }
    .total-box {
      font-size: 1.5rem;
      font-weight: bold;
      margin-top: 30px;
    }
  </style>
</head>
<body>
<section class="text-center">
  <div class="p-5 bg-image" style="background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg'); height: 200px;"></div>
  <div class="card mx-4 mx-md-5 shadow-5-strong bg-body-tertiary" style="margin-top: -80px; backdrop-filter: blur(30px); max-width: 1000px; margin-left:auto; margin-right:auto;">
    <div class="card-body py-5 px-md-5">
      <h2 class="fw-bold mb-5">Registrar Venta</h2>
      <form id="ventaForm" method="POST" action="procesar_venta.php">
        <!-- Datos cliente y empleado -->
        <div class="row mb-4">
          <div class="col-md-4">
            <label for="idCliente" class="form-label">Cliente ID</label>
            <input type="number" name="idCliente" id="idCliente" class="form-control" required />
          </div>
          <div class="col-md-4">
            <label for="idEmpleado" class="form-label">Empleado ID</label>
            <input type="number" name="idEmpleado" id="idEmpleado" class="form-control" required />
          </div>
          <div class="col-md-4">
            <label class="form-label">Fecha</label>
            <input type="text" name="fechaVenta" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly />
          </div>
        </div>

        <!-- Contenedor productos + total -->
        <div class="row">
          <div class="col-md-8">
            <div id="productosContainer">
              <h5>Productos en la venta</h5>
              <!-- Se añaden aquí -->
            </div>
            <button type="button" class="btn btn-success mt-3" id="agregarProductoBtn">
              Agregar Producto
            </button>
          </div>
          <div class="col-md-4 text-start">
            <div class="total-box">
              Total: $<span id="totalVenta">0.00</span> MXN
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block mt-5">
          Procesar Venta
        </button>
      </form>
    </div>
  </div>
</section>

<script>
  const productosDisponibles = <?php echo json_encode($productos); ?>;
</script>

<script>
  const productosContainer = document.getElementById('productosContainer');
  const agregarProductoBtn = document.getElementById('agregarProductoBtn');
  const totalVentaSpan = document.getElementById('totalVenta');

  function crearProductoItem() {
    const div = document.createElement('div');
    div.classList.add('row', 'mb-2', 'align-items-center', 'producto-item');

    div.innerHTML = `
      <div class="col-md-6">
        <select name="productos[][idProducto]" class="form-select producto-select" required>
          <option value="">Selecciona un producto</option>
          ${productosDisponibles.map(p => 
            `<option value="${p.idProducto}" data-precio="${p.PrecioVenta}">${p.Nombre}</option>`
          ).join('')}
        </select>
      </div>
      <div class="col-md-3">
        <input type="number" name="productos[][Cantidad]" class="form-control cantidad-input" min="1" value="1" required />
      </div>
      <div class="col-md-2 text-start align-self-center">
        <span class="remove-btn" title="Quitar producto">&times;</span>
      </div>
    `;

    div.querySelector('.remove-btn').addEventListener('click', () => {
      div.remove();
      calcularTotal();
    });

    div.querySelector('.producto-select').addEventListener('change', calcularTotal);
    div.querySelector('.cantidad-input').addEventListener('input', calcularTotal);

    return div;
  }

  agregarProductoBtn.addEventListener('click', () => {
    const nuevoProducto = crearProductoItem();
    productosContainer.appendChild(nuevoProducto);
  });

  function calcularTotal() {
    let total = 0;
    document.querySelectorAll('.producto-item').forEach(item => {
      const select = item.querySelector('.producto-select');
      const cantidadInput = item.querySelector('.cantidad-input');
      const precio = parseFloat(select.selectedOptions[0]?.dataset.precio || 0);
      const cantidad = parseInt(cantidadInput.value) || 0;
      total += precio * cantidad;
    });
    totalVentaSpan.textContent = total.toFixed(2);
  }

  window.onload = () => {
    agregarProductoBtn.click();
  };
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
