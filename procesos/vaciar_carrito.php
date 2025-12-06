<?php
session_start();

if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    $_SESSION['carrito_mensaje_temp'] = [
        'tipo' => 'info',
        'titulo' => 'Carrito vaciado',
        'mensaje' => 'Todos los productos se han eliminado del carrito'
    ];

    unset($_SESSION['carrito']);
} else {
    $_SESSION['carrito_mensaje_temp'] = [
        'tipo' => 'warning',
        'titulo' => 'Carrito vacío',
        'mensaje' => 'El carrito ya estaba vacío'
    ];
}

// Redirigir al carrito
header("Location: ../views/carrito.php");
exit();
