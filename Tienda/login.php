<?php
// Inicia una nueva sesion o reanuda la sesion existente en el servidor
session_start();

// Establece una conexion con la base de datos MySQL llamada tienda_ropa en localhost usando el usuario root sin contrasena
$conexion = new mysqli("localhost", "root", "", "tienda_ropa");

// Evalua si ocurrio un error al intentar conectar con la base de datos
if ($conexion->connect_error) {
    // Detiene la ejecucion del script y muestra el mensaje de error de la conexion
    die("Error de conexión: " . $conexion->connect_error);
}

// Comprueba si el metodo de envio de datos es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene el email del formulario y quita los espacios en blanco extra al inicio y final
    $email = trim($_POST['email']);
    // Almacena la contrasena recibida desde el formulario
    $password = $_POST['password'];

    // Prepara la consulta SQL para buscar el id, nombre y contrasena del usuario por su email
    $stmt = $conexion->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ?");
    // Vincula la variable email como un texto ("s") al parametro de la consulta
    $stmt->bind_param("s", $email);
    // Ejecuta la consulta preparada en la base de datos
    $stmt->execute();
    // Obtiene los resultados generados por la consulta
    $resultado = $stmt->get_result();

    // Obtiene la fila como un arreglo asociativo y valida si el usuario existe
    if ($usuario = $resultado->fetch_assoc()) {
        // Compara la contrasena ingresada con el hash guardado en la base de datos
        if (password_verify($password, $usuario['password'])) {
            // Guarda el id del usuario en las variables de sesion
            $_SESSION['usuario_id'] = $usuario['id'];
            // Guarda el nombre del usuario en las variables de sesion
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            
            // Muestra un mensaje de inicio exitoso y el enlace a la pagina principal
            echo "Inicio de sesion correcto, Bienvenido. <a href='Productos.php'>Ir a la pagina principal</a>";
        } else {
            // Muestra un mensaje si la contrasena no coincide
            echo "Contraseña incorrecta. <a href='login.php'>Volver a intentar</a>";
        }
    } else {
        // Muestra un mensaje si el correo no esta registrado
        echo "El correo no esta registrado. <a href='registro.php'>Crear cuenta</a>";
    }
    
    // Cierra la sentencia preparada para liberar memoria
    $stmt->close();
}

// Cierra la conexion activa con la base de datos
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Coppel</title>
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

    <div class="main-container">
        <div class="cards-grid">

            <form action="login.php" method="POST">
                <h2>Iniciar Sesión</h2>
                
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
              
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
              
                <button type="submit">Ingresar</button>
              </form>

        </div>
    </div>


</body>
<footer>
  <p> Armando Eliseo Alvarez Carreon 5ºI 0482 </p>
</footer>
</html>
