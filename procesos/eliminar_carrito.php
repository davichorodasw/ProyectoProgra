<?php
session_start();

if (isset($_POST['item_index']) && isset($_SESSION['carrito'][$_POST['item_index']])) {
    $index = intval($_POST['item_index']);

    $producto_eliminado = $_SESSION['carrito'][$index]['titulo'];

    unset($_SESSION['carrito'][$index]);

    $_SESSION['carrito'] = array_values($_SESSION['carrito']);

    $_SESSION['carrito_mensaje_temp'] = [
        'tipo' => 'info',
        'titulo' => 'Producto eliminado',
        'mensaje' => $producto_eliminado . ' se eliminó del carrito'
    ];
} else {
    $_SESSION['carrito_mensaje_temp'] = [
        'tipo' => 'error',
        'titulo' => 'Error',
        'mensaje' => 'No se pudo eliminar el producto'
    ];
}

header("Location: ../views/carrito.php");
exit();
