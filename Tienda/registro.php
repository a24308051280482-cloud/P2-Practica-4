<?php
// Inicia una nueva conexion a la base de datos MySQL "tienda_ropa" en el servidor "localhost" con el usuario "root" y sin contraseña
$conexion = new mysqli("localhost", "root", "", "tienda_ropa");

// Evalua si ocurrio algun error durante el intento de conexion a la base de datos
if ($conexion->connect_error) {
    // Detiene la ejecucion del script y muestra el mensaje de error reportado por MySQL
    die("Error de conexion: " . $conexion->connect_error);
}

// Comprueba si los datos del formulario fueron enviados mediante el metodo HTTP POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene el campo 'nombre' del formulario y remueve los espacios en blanco al inicio y al final
    $nombre = trim($_POST['nombre']);
    // Obtiene el campo 'email' del formulario y elimina los espacios en blanco innecesarios
    $email = trim($_POST['email']);
    // Almacena la contraseña introducida por el usuario en el formulario
    $password = $_POST['password'];

    // Prepara una consulta SQL segura con un marcador de posicion (?) para buscar si el correo ya existe
    $stmt_check = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
    // Vincula la variable $email al marcador de posicion indicado por el tipo de dato string ("s")
    $stmt_check->bind_param("s", $email);
    // Ejecuta la consulta en la base de datos
    $stmt_check->execute();
    // Obtiene el resultado de la consulta ejecutada
    $resultado = $stmt_check->get_result();

    // Comprueba si el numero de filas obtenidas es mayor a cero (indica que el email ya esta registrado)
    if ($resultado->num_rows > 0) {
        // Muestra un aviso informando que el correo electronico ya se encuentra en uso
        echo "Este correo ya esta registrado. Intenta iniciar sesion.";
    } else {
        // Crea un hash encriptado y seguro de la contraseña usando el algoritmo por defecto de PHP
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Prepara la instruccion SQL para insertar un nuevo registro en la tabla 'usuarios'
        $stmt_insert = $conexion->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        // Vincula los tres parametros ($nombre, $email, $password_hash) como cadenas de texto ("sss")
        $stmt_insert->bind_param("sss", $nombre, $email, $password_hash);

        // Ejecuta la orden de insercion y verifica si la operacion fue exitosa
        if ($stmt_insert->execute()) {
            // Imprime un mensaje de confirmacion de registro junto con un enlace a la pagina de login
            echo "Usuario registrado con exito <a href='login.php'>Iniciar sesion</a>";
        } else {
            // Imprime un mensaje indicando que no se pudo completar el registro
            echo "Hubo un error al registrar la cuenta.";
        }

        // Cierra la sentencia preparada de insercion para liberar recursos del servidor
        $stmt_insert->close();
    }

    // Cierra la sentencia preparada de verificacion para liberar recursos
    $stmt_check->close();
}

// Cierra la conexion activa a la base de datos
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

        <form action="registro.php" method="POST">

            <h2>Crear Cuenta</h2>

            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Registrarse</button>
            </form>

        </div>
    </div>


</body>
<footer>
  <p> Armando Eliseo Alvarez Carreon 5ºI 0482 </p>
</footer>
</html>