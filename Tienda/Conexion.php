<?php
// Define el nombre del servidor de la base de datos
$servidor = "localhost";
// Define el nombre del usuario para acceder a MySQL
$usuario  = "root";        
// Define la contraseña de acceso (vacia por defecto en entornos locales)
$password = "";      
// Define el nombre de la base de datos a la que se quiere conectar
$base_datos = "tienda_ropa"; 

// Establece la conexion con la base de datos MySQL usando el estilo procedural de mysqli
$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

// Evalua si la conexion fallo o no se pudo establecer
if (!$conexion) {
    // Detiene la ejecucion del script y muestra el mensaje de error devuelto por MySQL
    die("Error de conexión: " . mysqli_connect_error());
}
?>
