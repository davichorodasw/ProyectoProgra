<?php
session_start();

header('Content-Type: application/json');

if (
    !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true ||
    !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin' // verifica si es admin para poder crear
) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$pedido_id = isset($_POST['pedido_id']) ? intval($_POST['pedido_id']) : 0;
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';

if ($pedido_id <= 0 || empty($estado)) {
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos',
        'received' => ['pedido_id' => $pedido_id, 'estado' => $estado]
    ]);
    exit;
}

$estados_permitidos = ['pendiente', 'procesando', 'completado', 'cancelado'];
if (!in_array($estado, $estados_permitidos)) {
    echo json_encode([
        'success' => false,
        'message' => 'Estado no válido',
        'estado_recibido' => $estado
    ]);
    exit;
}

require_once 'conexion.php';

try {
    $conn = new mysqli('localhost', 'root', '', 'ritmoretro');

    if ($conn->connect_error) {
        throw new Exception('Error de conexión a la base de datos');
    }

    $conn->set_charset('utf8');

    $sql_check = "SELECT id FROM pedidos WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);

    if (!$stmt_check) {
        throw new Exception('Error al preparar verificación');
    }

    $stmt_check->bind_param('i', $pedido_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows === 0) {
        $stmt_check->close();
        $conn->close();
        echo json_encode(['success' => false, 'message' => 'Pedido no encontrado']);
        exit;
    }

    $stmt_check->close();

    $sql_update = "UPDATE pedidos SET estado = ?, fecha_actualizacion = NOW() WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);

    if (!$stmt_update) {
        throw new Exception('Error al preparar actualización');
    }

    $stmt_update->bind_param('si', $estado, $pedido_id);

    if (!$stmt_update->execute()) {
        throw new Exception('Error al ejecutar actualización');
    }

    $affected_rows = $stmt_update->affected_rows;
    $stmt_update->close();

    if ($estado === 'cancelado') {
        $sql_detalles = "SELECT producto_id, cantidad FROM detalles_pedido WHERE pedido_id = ?";
        $stmt_detalles = $conn->prepare($sql_detalles);

        if ($stmt_detalles) {
            $stmt_detalles->bind_param('i', $pedido_id);
            $stmt_detalles->execute();
            $result_detalles = $stmt_detalles->get_result();

            while ($detalle = $result_detalles->fetch_assoc()) {
                $sql_stock = "UPDATE productos SET stock = stock + ? WHERE id = ?"; // si se cancela el pedido se debe devolver el stock qie se tenía del producto
                $stmt_stock = $conn->prepare($sql_stock);

                if ($stmt_stock) {
                    $stmt_stock->bind_param('ii', $detalle['cantidad'], $detalle['producto_id']);
                    $stmt_stock->execute();
                    $stmt_stock->close();
                }
            }

            $stmt_detalles->close();
        }
    }

    $conn->close();

    echo json_encode([
        'success' => true,
        'message' => 'Estado actualizado correctamente',
        'pedido_id' => $pedido_id,
        'nuevo_estado' => $estado,
        'affected_rows' => $affected_rows
    ]);
} catch (Exception $e) {
    if (ob_get_length()) ob_clean();

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error interno: ' . $e->getMessage()
    ]);
}
