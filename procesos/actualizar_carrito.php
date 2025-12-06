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

    require_once '../php/conexion.php';
    $conn = conectarDB();

    $query = "SELECT stock FROM productos WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $producto_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $producto = mysqli_fetch_assoc($result);
        $stock_disponible = $producto['stock'];

        if ($accion == 'mas') {
            if ($cantidad_actual < $stock_disponible) {
                $nueva_cantidad = $cantidad_actual + 1;
                $_SESSION['carrito'][$index]['cantidad'] = $nueva_cantidad;
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
                $nueva_cantidad = $cantidad_actual - 1;
                $_SESSION['carrito'][$index]['cantidad'] = $nueva_cantidad;
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

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['carrito_mensaje_temp'] = [
            'tipo' => 'error',
            'titulo' => 'Error',
            'mensaje' => 'Producto no encontrado en la base de datos'
        ];
    }

    mysqli_close($conn);
}

header("Location: ../views/carrito.php");
exit();
