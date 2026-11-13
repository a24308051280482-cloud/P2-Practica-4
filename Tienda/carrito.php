<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "tienda_ropa");

// Eliminar producto si se solicita
if (isset($_POST['eliminar_id'])) {
    $id_eliminar = intval($_POST['eliminar_id']);
    unset($_SESSION['carrito'][$id_eliminar]);
    header("Location: carrito.php");
    exit();
}

// Vaciar carrito
if (isset($_POST['vaciar'])) {
    unset($_SESSION['carrito']);
    header("Location: carrito.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Coppel - Mi Carrito</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      background-color: #f4f6f8;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background-color: #2b3e50;
      padding: 12px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 25px;
    }

    .brand {
      font-size: 24px;
      font-weight: bold;
      color: #ffffff;
      text-decoration: none;
    }

    nav {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    nav a {
      color: #e0e6ed;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: color 0.2s ease;
    }

    nav a:hover {
      color: #3197e8;
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .search-box {
      display: flex;
      align-items: center;
    }

    .search-box input {
      padding: 8px 12px;
      border: none;
      border-radius: 4px 0 0 4px;
      outline: none;
      font-size: 13px;
      width: 180px;
    }

    .search-box button {
      padding: 8px 12px;
      border: none;
      background-color: #3197e8;
      color: white;
      border-radius: 0 4px 4px 0;
      cursor: pointer;
      font-size: 13px;
    }

    .auth-buttons {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .btn-header {
      padding: 7px 14px;
      border-radius: 4px;
      font-size: 13px;
      font-weight: bold;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .btn-cart {
      background-color: #28a745;
      color: #ffffff;
    }

    .btn-cart:hover {
      background-color: #218838;
    }

    .btn-login {
      background-color: #3197e8;
      color: #ffffff;
    }

    .btn-login:hover {
      background-color: #257bc4;
    }

    .btn-register {
      background-color: transparent;
      color: #ffffff;
      border: 1px solid #3197e8;
    }

    .btn-register:hover {
      background-color: #3197e8;
    }

    main {
      flex: 1;
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      padding: 30px 20px;
    }

    .section-block {
      margin-bottom: 45px;
      scroll-margin-top: 80px;
    }

    .section-title {
      font-size: 22px;
      color: #2b3e50;
      margin-bottom: 20px;
      font-weight: bold;
      border-bottom: 2px solid #3197e8;
      padding-bottom: 8px;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 20px;
    }

    .product-card {
      background-color: #ffffff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }

    .product-img {
      width: 100%;
      height: 160px;
      background-color: #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
      font-size: 14px;
      font-weight: bold;
      text-align: center;
      padding: 10px;
    }

    .product-info {
      padding: 15px;
      display: flex;
      flex-direction: column;
      flex: 1;
    }

    .product-category {
      font-size: 11px;
      text-transform: uppercase;
      color: #3197e8;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .product-name {
      font-size: 14px;
      color: #2b3e50;
      font-weight: bold;
      margin-bottom: 10px;
      min-height: 36px;
      line-height: 1.3;
    }

    .product-price {
      font-size: 17px;
      color: #d9534f;
      font-weight: bold;
      margin-top: auto;
      margin-bottom: 12px;
    }

    .btn-buy {
      width: 100%;
      padding: 9px 0;
      background-color: #3197e8;
      color: white;
      border: none;
      border-radius: 4px;
      font-weight: bold;
      font-size: 13px;
      cursor: pointer;
      text-align: center;
      transition: background-color 0.2s ease;
    }

    .btn-buy:hover {
      background-color: #257bc4;
    }
    footer {
      font-family: Arial, sans-serif;
      background-color: #2c3e50; 
      margin: 0; 
      padding: 20px; 
      text-align: center; 
      color: white;
    }
        table { 
      width: 100%; 
      border-collapse: collapse; 
      margin-top: 20px; 
    }

    th, td { 
      padding: 12px; 
      text-align: left; 
      border-bottom: 1px solid #ddd; 
    }

    th { 
      background-color: #2b3e50; 
      color: white; 
    }

    .total-box { 
      margin-top: 20px; 
      text-align: right; 
      font-size: 20px; 
      font-weight: bold; 
      color: #d9534f; 
    }

    .btn-action { 
      padding: 8px 12px; 
      border: none; 
      border-radius: 4px; 
      cursor: pointer; 
      text-decoration: none; 
      font-weight: bold; 
    }

    .btn-delete { 
      background-color: #d9534f; 
      color: white; 
    }

    .btn-back { 
      background-color: #3197e8; 
      color: white; 
      display: inline-block; 
      margin-top: 15px; 
    }
  </style>
</head>
<body>

  <header>
    <div class="header-left">
      <a href="Productos.php" class="brand">Coppel</a>
    </div>

    <div class="header-right">
      <div class="search-box">
        <input type="text" placeholder="Buscar productos...">
        <button type="button">Buscar</button>
      </div>

      <div class="auth-buttons">
        <!-- Botón del Carrito con contador -->
         <a href="carrito.php" class="btn-header btn-cart">Carrito</a>
        <a href="login.php" class="btn-header btn-login">Iniciar sesión</a>
        <a href="registro.php" class="btn-header btn-register">Registrar</a>
      </div>
    </div>
  </header>
<div class="container">
  <h1>Carrito de Compras</h1>

  <?php if (empty($_SESSION['carrito'])): ?>
    <p>Tu carrito está vacío.</p>
    <a href="Productos.php" class="btn-action btn-back">Volver a la tienda</a>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Producto</th>
          <th>Categoría</th>
          <th>Precio Unitario</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $ids = implode(',', array_keys($_SESSION['carrito']));
        $sql = "SELECT * FROM productos WHERE id IN ($ids)";
        $resultado = $conexion->query($sql);
        $grand_total = 0;

        while ($row = $resultado->fetch_assoc()):
            $id = $row['id'];
            $cantidad = $_SESSION['carrito'][$id];
            $subtotal = $row['precio'] * $cantidad;
            $grand_total += $subtotal;
        ?>
        <tr>
          <td><?php echo htmlspecialchars($row['nombre']); ?></td>
          <td><?php echo htmlspecialchars($row['categoria']); ?></td>
          <td>$<?php echo number_format($row['precio'], 2); ?> MXN</td>
          <td><?php echo $cantidad; ?></td>
          <td>$<?php echo number_format($subtotal, 2); ?> MXN</td>
          <td>
            <form action="carrito.php" method="POST" style="display:inline;">
              <input type="hidden" name="eliminar_id" value="<?php echo $id; ?>">
              <button type="submit" class="btn-action btn-delete">Eliminar</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="total-box">
      Total a pagar: $<?php echo number_format($grand_total, 2); ?> MXN
    </div>

    <div style="margin-top: 20px; display: flex; justify-content: space-between;">
      <a href="Productos.php" class="btn-action btn-back">Seguir comprando</a>
      
      <form action="carrito.php" method="POST">
        <button type="submit" name="vaciar" class="btn-action btn-delete">Vaciar Carrito</button>
      </form>
    </div>
  <?php endif; ?>
</div>

</body>
<footer>
  <p> Armando Eliseo Alvarez Carreon 5ºI 0482 </p>
</footer>
</html>