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
    body { font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px; }
    .container { max-width: 900px; margin: 0 auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    h1 { color: #2b3e50; border-bottom: 2px solid #3197e8; padding-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background-color: #2b3e50; color: white; }
    .total-box { margin-top: 20px; text-align: right; font-size: 20px; font-weight: bold; color: #d9534f; }
    .btn-action { padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-weight: bold; }
    .btn-delete { background-color: #d9534f; color: white; }
    .btn-back { background-color: #3197e8; color: white; display: inline-block; margin-top: 15px; }
  </style>
</head>
<body>

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
</html>