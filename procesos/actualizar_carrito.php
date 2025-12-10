<?php
session_start();

if (isset($_POST['item_index']) && isset($_POST['accion'])) {
    $index = intval($_POST['item_index']);
    $accion = $_POST['accion'];

    if (!isset($_SESSION['carrito'][$index])) {
        $_SESSION['carrito_mensaje_temp'] = [
            'tipo' => 'error',
            'titulo' => 'Error',
            'mensaje' => 'El producto no existe en el carrito'
        ];
        header("Location: ../views/carrito.php");
        exit();
    }

    $producto_id = $_SESSION['carrito'][$index]['id'];
    $cantidad_actual = $_SESSION['carrito'][$index]['cantidad'];
    $nueva_cantidad = $cantidad_actual;

    require_once '../php/manejoCarrito.php';
    $producto = obtenerStockProducto($producto_id);

    if ($producto !== null) {
        $stock_disponible = $producto['stock'];

        if ($accion == 'mas') {
            if ($cantidad_actual < $stock_disponible) {
                $_SESSION['carrito'][$index]['cantidad'] = $cantidad_actual + 1;
                $_SESSION['carrito_mensaje_temp'] = [
                    'tipo' => 'success',
                    'titulo' => 'Cantidad actualizada',
                    'mensaje' => 'Se aumentó la cantidad del producto'
                ];
            } else {
                $_SESSION['carrito_mensaje_temp'] = [
                    'tipo' => 'warning',
                    'titulo' => 'Stock limitado',
                    'mensaje' => 'No hay más stock disponible de este producto'
                ];
            }
        } elseif ($accion == 'menos') {
            if ($cantidad_actual > 1) {
                $_SESSION['carrito'][$index]['cantidad'] = $cantidad_actual - 1;
                $_SESSION['carrito_mensaje_temp'] = [
                    'tipo' => 'success',
                    'titulo' => 'Cantidad actualizada',
                    'mensaje' => 'Se redujo la cantidad del producto'
                ];
            } else {
                $_SESSION['carrito_mensaje_temp'] = [
                    'tipo' => 'info',
                    'titulo' => 'Cantidad mínima',
                    'mensaje' => 'La cantidad mínima es 1'
                ];
            }
        }

        $_SESSION['carrito'][$index]['stock'] = $stock_disponible;
    } else {
        $_SESSION['carrito_mensaje_temp'] = [
            'tipo' => 'error',
            'titulo' => 'Error',
            'mensaje' => 'Producto no encontrado en la base de datos'
        ];
    }
}

header("Location: ../views/carrito.php");
exit();
