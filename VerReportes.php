<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Menú Principal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      color: white;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    h1 {
      text-align: center;
      margin-top: 3rem;
      margin-bottom: 3rem;
      font-size: 3rem;
      text-shadow: 2px 2px 6px rgba(0,0,0,0.7);
      letter-spacing: 2px;
      font-weight: 700;
    }

    .menu-container {
      max-width: 900px;
      margin: 0 auto 4rem;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1.5rem;
    }

    .menu-item {
      background: linear-gradient(135deg, #2c5364, #203a43, #0f2027);
      border: none;
      border-radius: 12px;
      padding: 3.4rem 0;
      font-size: 1.2rem;
      font-weight: 600;
      text-align: center;
      color: white;
      text-decoration: none;
      box-shadow: 0 4px 10px rgba(0,0,0,0.5);
      transition: background 0.3s ease, transform 0.2s ease;
      user-select: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

       .btn-salir {
      position: fixed;
      top: 15px;
      right: 15px;
      background: linear-gradient(135deg, #2c5364, #203a43, #0f2027);
      border: none;
      border-radius: 8px;
      padding: 0.6rem 1.4rem;
      font-size: 1.1rem;
      font-weight: 700;
      color: white;
      cursor: pointer;
      box-shadow: 0 3px 8px rgba(0,0,0,0.5);
      transition: background 0.3s ease, transform 0.15s ease;
      z-index: 1000;
    }

    .menu-item:hover, .menu-item:focus {
      background: linear-gradient(135deg, #4e768a, #3b5864, #22303a);
      transform: scale(1.05);
      color: #d1e7ff;
      text-decoration: none;
      outline: none;
    }
  </style>
</head>
<body>
<button class="btn-salir" onclick="window.location.href='pagina1.php'">Salir</button>
  <h1>Menú Reportes</h1>

  <div class="menu-container">
    <a href="VerPedidos.php" class="menu-item">Pedidos</a>
    <a href="ProductosNoAptos.php" class="menu-item">Productos no aptos</a>
    <a href="TablaProductoProvedor.php" class="menu-item">Productos por Provedor</a>
    <a href="VerEmpleados.php" class="menu-item">Productos Vendidos por Provedor</a>
    
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
