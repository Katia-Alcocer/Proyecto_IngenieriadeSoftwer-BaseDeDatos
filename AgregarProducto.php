<?php
require_once '../conexion.php';

function obtenerCategorias($pdo) {
    $stmt = $pdo->query("SELECT idCategoria, Nombre FROM Categorias");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerProveedores($pdo) {
    $stmt = $pdo->query("SELECT idProveedor, Nombre FROM Proveedores");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$categorias = obtenerCategorias($pdo);
$proveedores = obtenerProveedores($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación básica
    $errores = [];

    if (empty($_POST["Nombre"])) $errores[] = "El nombre es obligatorio.";
    if (!is_numeric($_POST["PrecioCompra"])) $errores[] = "El precio de compra no es válido.";
    if (!is_numeric($_POST["Stock"])) $errores[] = "El stock no es válido.";
    if (!is_numeric($_POST["idCategoria"])) $errores[] = "Debe seleccionar una categoría válida.";
    if (!is_numeric($_POST["idProveedor"])) $errores[] = "Debe seleccionar un proveedor válido.";
    if (!is_numeric($_POST["Peso1"])) $errores[] = "El peso 1 no es válido.";
    if (!is_numeric($_POST["Peso2"])) $errores[] = "El peso 2 no es válido.";

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("EXEC sp_RegistrarProductoConValidacion 
                @Nombre = ?, 
                @PrecioCompra = ?, 
                @Stock = ?, 
                @Estado = ?, 
                @idCategoria = ?, 
                @idProveedor = ?, 
                @Peso1 = ?, 
                @Peso2 = ?, 
                @C_extra = ?");

            $stmt->execute([
                $_POST["Nombre"],
                floatval($_POST["PrecioCompra"]),
                intval($_POST["Stock"]),
                isset($_POST["Estado"]) ? 1 : 0,
                intval($_POST["idCategoria"]),
                intval($_POST["idProveedor"]),
                floatval($_POST["Peso1"]),
                floatval($_POST["Peso2"]),
                $_POST["C_extra"] !== '' ? $_POST["C_extra"] : null
            ]);

            echo "<script>
                    alert('Producto registrado con éxito.');
                    window.location.href = 'VerProductos.php';
                  </script>";
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al registrar el producto: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Producto</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<section class="text-center">
    <div class="p-5 bg-image" style="background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg'); height: 300px;"></div>
    <div class="card mx-4 mx-md-5 shadow-5-strong bg-body-tertiary" style="margin-top: -100px; backdrop-filter: blur(30px);">
        <div class="card-body py-5 px-md-5">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-10">
                    <h2 class="fw-bold mb-5">Registrar Producto</h2>

                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errores as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" onsubmit="this.querySelector('button[type=submit]').disabled = true;">

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="Nombre" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Precio de Compra</label>
                                <input type="number" step="0.01" name="PrecioCompra" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Stock</label>
                                <input type="number" name="Stock" class="form-control" required>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label">Estado</label><br>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="Estado" id="estadoSwitch" checked>
                                    <label class="form-check-label" for="estadoSwitch">Activo</label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label">Categoría</label>
                                <select name="idCategoria" class="form-control" required>
                                    <option value="" disabled selected>Seleccione una categoría</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?= htmlspecialchars($categoria['idCategoria']) ?>">
                                            <?= htmlspecialchars($categoria['Nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Proveedor</label>
                                <select name="idProveedor" class="form-control" required>
                                    <option value="" disabled selected>Seleccione un proveedor</option>
                                    <?php foreach ($proveedores as $proveedor): ?>
                                        <option value="<?= htmlspecialchars($proveedor['idProveedor']) ?>">
                                            <?= htmlspecialchars($proveedor['Nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-3 mb-4">
                                <label class="form-label">Peso 1 (kg)</label>
                                <input type="number" step="0.01" name="Peso1" class="form-control" required>
                            </div>

                            <div class="col-md-3 mb-4">
                                <label class="form-label">Peso 2 (kg)</label>
                                <input type="number" step="0.01" name="Peso2" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Comentario Extra (opcional)</label>
                            <input type="text" name="C_extra" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mb-4">Registrar</button>
                        <button type="button" class="btn btn-secondary btn-block mb-4" onclick="window.location.href='VerProductos.php'">Salir</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
