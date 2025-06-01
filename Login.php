//http://localhost:8081/Login.php


<?php
require_once("conexion.php"); // Incluye la conexión una sola vez

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM Usuarios WHERE Email = ? AND Contrasena = ?";
    $params = array($email, $password);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $mensaje = " Inicio de sesión exitoso.";
    } else {
        $mensaje = " Usuario o contraseña incorrectos.";
    }

    sqlsrv_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h2>Login</h2>

    <p><?php echo $mensaje; ?></p>

    <form action="Login.php" method="POST">
        <label>Correo:</label>
        <input type="email" name="email" required><br><br>

        <label>Contraseña:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Ingresar">
    </form>
</body>
</html>
