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

</html>
