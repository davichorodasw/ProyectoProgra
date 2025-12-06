<?php
session_start();

// Verificar si es admin
if (
    !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true ||
    !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'
) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$pedido_id = $_POST['pedido_id'] ?? 0;
$estado = $_POST['estado'] ?? '';

// Validar datos
if (empty($pedido_id) || empty($estado)) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// Validar estado
$estados_permitidos = ['pendiente', 'procesando', 'completado', 'cancelado'];
if (!in_array($estado, $estados_permitidos)) {
    echo json_encode(['success' => false, 'message' => 'Estado no válido']);
    exit;
}

require_once 'conexion.php';
$conn = conectarDB();

// Actualizar estado del pedido
$query = "UPDATE pedidos SET estado = ?, fecha_actualizacion = NOW() WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Error en la consulta']);
    exit;
}

mysqli_stmt_bind_param($stmt, "si", $estado, $pedido_id);
$resultado = mysqli_stmt_execute($stmt);

if ($resultado) {
    // Si se cancela un pedido, devolver stock
    if ($estado === 'cancelado') {
        // Obtener detalles del pedido para devolver stock
        $detalles_query = "SELECT producto_id, cantidad FROM detalles_pedido WHERE pedido_id = ?";
        $detalles_stmt = mysqli_prepare($conn, $detalles_query);
        mysqli_stmt_bind_param($detalles_stmt, "i", $pedido_id);
        mysqli_stmt_execute($detalles_stmt);
        $detalles_result = mysqli_stmt_get_result($detalles_stmt);

        while ($detalle = mysqli_fetch_assoc($detalles_result)) {
            $update_stock = "UPDATE productos SET stock = stock + ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $update_stock);
            mysqli_stmt_bind_param($update_stmt, "ii", $detalle['cantidad'], $detalle['producto_id']);
            mysqli_stmt_execute($update_stmt);
            mysqli_stmt_close($update_stmt);
        }

        mysqli_stmt_close($detalles_stmt);
    }

    echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
