<?php
require_once 'conexion.php';

class ManejoPedidos
{
    public static function obtenerEstadisticasDashboard()
    {
        $conn = conectarDB();

        $sql = "SELECT 
                    COUNT(*) AS total_pedidos,
                    SUM(CASE WHEN estado = 'completado' THEN 1 ELSE 0 END) AS completados,
                    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) AS pendientes,
                    SUM(CASE WHEN estado = 'cancelado' THEN 1 ELSE 0 END) AS cancelados,
                    SUM(total) AS ingresos_totales,
                    AVG(total) AS promedio_compra
                FROM pedidos";

        $result = $conn->query($sql);
        $data = $result->fetch_assoc() ?? [];

        $conn->close();
        return $data;
    }

    public static function obtenerUltimosPedidosDashboard($limite = 5)
    {
        $conn = conectarDB();

        $sql = "SELECT p.*, u.nombre AS cliente
                FROM pedidos p
                JOIN usuarios u ON u.id = p.usuario_id
                ORDER BY p.fecha_pedido DESC
                LIMIT ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $result = $stmt->get_result();

        $pedidos = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();
        return $pedidos;
    }

    public static function obtenerProductosBajoStock($limite = 5)
    {
        $conn = conectarDB();

        $sql = "SELECT id, titulo, stock
                FROM productos
                WHERE stock <= 5
                ORDER BY stock ASC
                LIMIT ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();
        return $productos;
    }

    public static function obtenerUltimosProductos($limite = 5)
    {
        $conn = conectarDB();

        $sql = "SELECT id, titulo, precio, stock, fecha_creacion
                FROM productos
                ORDER BY fecha_creacion DESC
                LIMIT ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();
        return $productos;
    }

    public static function obtenerPedidosFiltrados($filters, $limit, $offset)
    {
        $conn = conectarDB();

        $sql = "SELECT 
                    p.*, 
                    u.nombre AS cliente_nombre,
                    u.email AS cliente_email,
                    (SELECT COUNT(*) FROM detalles_pedido dp WHERE dp.pedido_id = p.id) AS total_productos,
                    (SELECT SUM(dp.cantidad) FROM detalles_pedido dp WHERE dp.pedido_id = p.id) AS total_items
                FROM pedidos p
                JOIN usuarios u ON p.usuario_id = u.id
                WHERE 1 = 1";

        $params = [];
        $types = "";

        if (!empty($filters["search"])) {
            $sql .= " AND (u.nombre LIKE ? OR u.email LIKE ? OR p.id LIKE ?)";
            $searchTerm = "%" . $filters["search"] . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "sss";
        }

        if (!empty($filters["estado"]) && $filters["estado"] !== "all") {
            $sql .= " AND p.estado = ?";
            $params[] = $filters["estado"];
            $types .= "s";
        }

        if (!empty($filters["fecha_inicio"])) {
            $sql .= " AND DATE(p.fecha_pedido) >= ?";
            $params[] = $filters["fecha_inicio"];
            $types .= "s";
        }

        if (!empty($filters["fecha_fin"])) {
            $sql .= " AND DATE(p.fecha_pedido) <= ?";
            $params[] = $filters["fecha_fin"];
            $types .= "s";
        }

        if (!empty($filters["filter"])) {
            if ($filters["filter"] === 'recent') {
                $sql .= " AND p.fecha_pedido >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            } elseif ($filters["filter"] === 'pending') {
                $sql .= " AND p.estado = 'pendiente'";
            } elseif ($filters["filter"] === 'completed') {
                $sql .= " AND p.estado = 'completado'";
            } elseif ($filters["filter"] === 'cancelled') {
                $sql .= " AND p.estado = 'cancelado'";
            } elseif ($filters["filter"] === 'high-value') {
                $sql .= " AND p.total >= 100";
            }
        }

        $sql .= " ORDER BY p.fecha_pedido DESC LIMIT ? OFFSET ?";

        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $pedidos = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();
        return $pedidos;
    }

    public static function obtenerConteoPedidosFiltrados($filters)
    {
        $conn = conectarDB();

        $sql = "SELECT COUNT(*) AS total
                FROM pedidos p
                JOIN usuarios u ON p.usuario_id = u.id
                WHERE 1 = 1";

        $params = [];
        $types = "";

        if (!empty($filters["search"])) {
            $sql .= " AND (u.nombre LIKE ? OR u.email LIKE ? OR p.id LIKE ?)";
            $searchTerm = "%" . $filters["search"] . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "sss";
        }

        if (!empty($filters["estado"]) && $filters["estado"] !== "all") {
            $sql .= " AND p.estado = ?";
            $params[] = $filters["estado"];
            $types .= "s";
        }

        if (!empty($filters["fecha_inicio"])) {
            $sql .= " AND DATE(p.fecha_pedido) >= ?";
            $params[] = $filters["fecha_inicio"];
            $types .= "s";
        }

        if (!empty($filters["fecha_fin"])) {
            $sql .= " AND DATE(p.fecha_pedido) <= ?";
            $params[] = $filters["fecha_fin"];
            $types .= "s";
        }

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $conteo = $result->fetch_assoc()["total"] ?? 0;

        $stmt->close();
        $conn->close();
        return $conteo;
    }

    public static function actualizarEstadoPedido($pedido_id, $estado)
    {
        $conn = conectarDB();

        $sqlCheck = "SELECT id FROM pedidos WHERE id = ?";
        $st = $conn->prepare($sqlCheck);
        $st->bind_param("i", $pedido_id);
        $st->execute();
        $st->store_result();

        if ($st->num_rows === 0) {
            $st->close();
            $conn->close();
            return ['success' => false, 'message' => 'Pedido no encontrado'];
        }
        $st->close();

        $sqlUpdate = "UPDATE pedidos SET estado = ?, fecha_actualizacion = NOW() WHERE id = ?";
        $stUpdate = $conn->prepare($sqlUpdate);
        $stUpdate->bind_param("si", $estado, $pedido_id);
        $stUpdate->execute();
        $stUpdate->close();

        if ($estado === 'cancelado') {
            $sqlDet = "SELECT producto_id, cantidad FROM detalles_pedido WHERE pedido_id = ?";
            $ds = $conn->prepare($sqlDet);
            $ds->bind_param("i", $pedido_id);
            $ds->execute();
            $resultDet = $ds->get_result();

            while ($fila = $resultDet->fetch_assoc()) {
                $sqlStock = "UPDATE productos SET stock = stock + ? WHERE id = ?";
                $ss = $conn->prepare($sqlStock);
                $ss->bind_param("ii", $fila["cantidad"], $fila["producto_id"]);
                $ss->execute();
                $ss->close();
            }
            $ds->close();
        }

        $conn->close();
        return ['success' => true, 'message' => 'Estado actualizado'];
    }
}
