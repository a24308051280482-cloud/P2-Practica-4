<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['producto_id'])) {
    $id = intval($_POST['producto_id']);

    // Si el carrito no existe en la sesión, se inicializa como un arreglo vacío
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }

    // Si el producto ya está en el carrito, se incrementa la cantidad; si no, se agrega con cantidad 1
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id] += 1;
    } else {
        $_SESSION['carrito'][$id] = 1;
    }
}

// Redirigir de vuelta al catálogo
header("Location: Productos.php");
exit();
?>