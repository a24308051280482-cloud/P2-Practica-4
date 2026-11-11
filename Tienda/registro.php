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
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f6fa;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        header p {
            margin: 10px 0 0 0;
            opacity: 0.8;
            font-size: 16px;
        }

        .main-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .cards-grid {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 1200px;
            width: 100%;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 320px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            box-sizing: border-box;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 22px;
            border-bottom: 2px solid #f5f6fa;
            padding-bottom: 15px;
        }

        .card p {
            color: #7f8c8d;
            font-size: 14px;
            line-height: 1.6;
            margin: 15px 0 25px 0;
            flex-grow: 1;
        }

        .btn {
            background: #3498db;
            color: white;
            text-decoration: none;
            padding: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            display: block;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #2980b9;
        }

        .btn-seguimiento {
            background: #e67e22;
        }

        .btn-seguimiento:hover {
            background: #d35400;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-size: 13px;
            background-color: #ffffff;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <header>
        <h1>Coppel</h1>
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

</html>