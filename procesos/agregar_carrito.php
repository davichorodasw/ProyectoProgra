<?php
session_start();

if (isset($_POST['producto_id'])) {
    $producto_id = intval($_POST['producto_id']);

    require_once '../php/conexion.php';
    $conn = conectarDB();

    $query = "SELECT id, titulo, artista, tipo, precio, imagen, stock FROM productos WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $producto_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $producto = mysqli_fetch_assoc($result);

        if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        if ($producto['stock'] <= 0) {
            $_SESSION['carrito_mensaje_temp'] = [
                'tipo' => 'warning',
                'titulo' => 'Sin stock',
                'mensaje' => $producto['titulo'] . ' está agotado'
            ];
        } else {
            $item_index = -1;
            foreach ($_SESSION['carrito'] as $index => $item) {
                if ($item['id'] == $producto_id) {
                    $item_index = $index;
                    break;
                }
            }

            if ($item_index >= 0) {
                if ($_SESSION['carrito'][$item_index]['cantidad'] < $producto['stock']) {
                    $_SESSION['carrito'][$item_index]['cantidad'] += 1;
                    $mensaje_tipo = 'success';
                    $mensaje_titulo = 'Cantidad actualizada';
                    $mensaje_texto = $producto['titulo'] . ' (ahora tienes ' . $_SESSION['carrito'][$item_index]['cantidad'] . ')';
                } else {
                    $mensaje_tipo = 'warning';
                    $mensaje_titulo = 'Stock limitado';
                    $mensaje_texto = 'No hay más stock disponible de ' . $producto['titulo'];
                }
            } else {
                $_SESSION['carrito'][] = [
                    'id' => $producto['id'],
                    'titulo' => $producto['titulo'],
                    'artista' => $producto['artista'],
                    'tipo' => $producto['tipo'],
                    'precio' => floatval($producto['precio']),
                    'imagen' => !empty($producto['imagen']) ? $producto['imagen'] : 'default.jpg',
                    'stock' => intval($producto['stock']),
                    'cantidad' => 1
                ];
                $mensaje_tipo = 'success';
                $mensaje_titulo = '¡Añadido al carrito!';
                $mensaje_texto = $producto['titulo'] . ' se añadió correctamente';
            }

            if (isset($mensaje_tipo)) {
                $_SESSION['carrito_mensaje_temp'] = [
                    'tipo' => $mensaje_tipo,
                    'titulo' => $mensaje_titulo,
                    'mensaje' => $mensaje_texto
                ];
            }
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['carrito_mensaje_temp'] = [
            'tipo' => 'error',
            'titulo' => 'Error',
            'mensaje' => 'Producto no encontrado'
        ];
    }

    mysqli_close($conn);
} else {
    $_SESSION['carrito_mensaje_temp'] = [
        'tipo' => 'error',
        'titulo' => 'Error',
        'mensaje' => 'No se especificó ningún producto'
    ];
}

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: ../views/carrito.php");
}
exit();
