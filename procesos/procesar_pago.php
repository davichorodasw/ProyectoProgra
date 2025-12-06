<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    $_SESSION['checkout_error'] = 'Debes iniciar sesión para realizar una compra';
    header("Location: ../views/login.php?redirect=checkout");
    exit();
}

if (empty($_SESSION['carrito'])) {
    $_SESSION['checkout_error'] = 'El carrito está vacío';
    header("Location: ../views/carrito.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['checkout_error'] = 'Método de solicitud no válido';
    header("Location: ../views/checkout.php");
    exit();
}

$required_fields = ['nombre', 'email', 'direccion', 'ciudad', 'codigo_postal', 'telefono', 'metodo_pago'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['checkout_error'] = 'Por favor, completa todos los campos obligatorios';
        header("Location: ../views/checkout.php");
        exit();
    }
}

//$metodos_validos = ['tarjeta', 'paypal', 'transferencia'];
$metodos_validos = ['tarjeta'];
if (!in_array($_POST['metodo_pago'], $metodos_validos)) {
    $_SESSION['checkout_error'] = 'Método de pago no válido';
    header("Location: ../views/checkout.php");
    exit();
}

if ($_POST['metodo_pago'] === 'tarjeta') {
    if (
        empty($_POST['numero_tarjeta']) || empty($_POST['nombre_tarjeta']) ||
        empty($_POST['vencimiento']) || empty($_POST['cvv'])
    ) {
        $_SESSION['checkout_error'] = 'Por favor, completa todos los datos de la tarjeta';
        header("Location: ../views/checkout.php");
        exit();
    }
}

require_once '../php/conexion.php';
$conn = conectarDB();

foreach ($_SESSION['carrito'] as $index => $item) {
    $producto_id = $item['id'];
    $cantidad_solicitada = $item['cantidad'];

    $query = "SELECT stock, titulo FROM productos WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $producto_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $producto = mysqli_fetch_assoc($result);

        if ($producto['stock'] < $cantidad_solicitada) {
            $_SESSION['checkout_error'] = "No hay suficiente stock de '" . $producto['titulo'] . "'. Stock disponible: " . $producto['stock'];
            header("Location: ../views/carrito.php");
            exit();
        }
    } else {
        $_SESSION['checkout_error'] = "El producto con ID $producto_id ya no está disponible";
        header("Location: ../views/carrito.php");
        exit();
    }

    mysqli_stmt_close($stmt);
}

$subtotal = 0;
foreach ($_SESSION['carrito'] as $item) {
    $subtotal += $item['precio'] * $item['cantidad'];
}

$envio = $subtotal >= 50 ? 0 : 5.00;
$total = $subtotal + $envio;

mysqli_begin_transaction($conn);

try {
    $usuario_id = $_SESSION['user_id'];
    $direccion_envio = mysqli_real_escape_string($conn, $_POST['direccion']);
    $ciudad = mysqli_real_escape_string($conn, $_POST['ciudad']);
    $codigo_postal = mysqli_real_escape_string($conn, $_POST['codigo_postal']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $metodo_pago = mysqli_real_escape_string($conn, $_POST['metodo_pago']);

    $query_pedido = "INSERT INTO pedidos (usuario_id, total, metodo_pago, direccion_envio, ciudad, codigo_postal, telefono, estado) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, 'pendiente')";

    $stmt_pedido = mysqli_prepare($conn, $query_pedido);
    mysqli_stmt_bind_param($stmt_pedido, "idsssss", $usuario_id, $total, $metodo_pago, $direccion_envio, $ciudad, $codigo_postal, $telefono);

    if (!mysqli_stmt_execute($stmt_pedido)) {
        throw new Exception("Error al crear el pedido: " . mysqli_error($conn));
    }

    $pedido_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt_pedido);

    foreach ($_SESSION['carrito'] as $item) {
        $producto_id = $item['id'];
        $cantidad = $item['cantidad'];
        $precio_unitario = $item['precio'];
        $subtotal_item = $cantidad * $precio_unitario;

        $query_detalle = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario, subtotal) 
                          VALUES (?, ?, ?, ?, ?)";

        $stmt_detalle = mysqli_prepare($conn, $query_detalle);
        mysqli_stmt_bind_param($stmt_detalle, "iiidd", $pedido_id, $producto_id, $cantidad, $precio_unitario, $subtotal_item);

        if (!mysqli_stmt_execute($stmt_detalle)) {
            throw new Exception("Error al crear detalle del pedido: " . mysqli_error($conn));
        }

        mysqli_stmt_close($stmt_detalle);

        $query_update_stock = "UPDATE productos SET stock = stock - ? WHERE id = ?";
        $stmt_update = mysqli_prepare($conn, $query_update_stock);
        mysqli_stmt_bind_param($stmt_update, "ii", $cantidad, $producto_id);

        if (!mysqli_stmt_execute($stmt_update)) {
            throw new Exception("Error al actualizar stock: " . mysqli_error($conn));
        }

        mysqli_stmt_close($stmt_update);
    }

    mysqli_commit($conn);

    $_SESSION['ultimo_pedido'] = [
        'id' => $pedido_id,
        'total' => $total,
        'fecha' => date('d/m/Y H:i'),
        'metodo_pago' => $metodo_pago,
        'direccion' => $direccion_envio,
        'ciudad' => $ciudad
    ];

    unset($_SESSION['carrito']);

    header("Location: ../views/confirmacion_pedido.php");
    exit();
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['checkout_error'] = "Error al procesar el pago: " . $e->getMessage();
    header("Location: ../views/checkout.php");
    exit();
} finally {
    mysqli_close($conn);
}
