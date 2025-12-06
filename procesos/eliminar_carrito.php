<?php
session_start();

if (isset($_POST['item_index']) && isset($_SESSION['carrito'][$_POST['item_index']])) {
    $index = intval($_POST['item_index']);

    // Guardar info del producto eliminado para mensaje
    $producto_eliminado = $_SESSION['carrito'][$index]['titulo'];

    // Eliminar el item
    unset($_SESSION['carrito'][$index]);

    // Reindexar el array
    $_SESSION['carrito'] = array_values($_SESSION['carrito']);

    // Notificación
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

// Redirigir al carrito
header("Location: ../views/carrito.php");
exit();
