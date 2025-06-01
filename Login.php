<?php
echo "Hola desde Login.php";
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Pildora</title>
    <link rel="icon" type="image/x-icon" href="imagenes/Logo1.jpg">
    <link rel="stylesheet" type="text/css" href="styleLogin.css">
</head>
<body>
  <div class="login">
    <h2>Iniciar Sesión</h2>
    <img src="imagenes/Login1.jpg" alt="Ícono de inicio de sesión">
    <form id="loginForm" method="POST" action="login.php">
        <label for="email">Usuario:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Ingresar</button>
    </form>

  </div>
</body>
</html>