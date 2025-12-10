<?php
require_once 'conexion.php';

function obtenerUsuarioPorEmail($email)
{
    $conn = conectarDB();

    $query = "SELECT id, nombre, email, password, rol FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $usuario ?: null;
}

function verificarEmailExistente($email)
{
    $conn = conectarDB();

    $query = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $row ?: null;
}

function crearUsuario($nombre, $email, $telefono, $passwordHash)
{
    $conn = conectarDB();

    $query = "INSERT INTO usuarios (nombre, email, telefono, password) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $email, $telefono, $passwordHash);

    $ok = mysqli_stmt_execute($stmt);
    $id = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $ok ? $id : false;
}

function eliminarUsuarioConPedidos($usuario_id)
{
    $conn = conectarDB();

    mysqli_begin_transaction($conn);

    try {
        // eliminar pedidos del usuario
        $stmt1 = mysqli_prepare($conn, "DELETE FROM pedidos WHERE usuario_id = ?");
        mysqli_stmt_bind_param($stmt1, "i", $usuario_id);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_close($stmt1);

        // eliminar usuario
        $stmt2 = mysqli_prepare($conn, "DELETE FROM usuarios WHERE id = ?");
        mysqli_stmt_bind_param($stmt2, "i", $usuario_id);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

        mysqli_commit($conn);
        mysqli_close($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        mysqli_close($conn);
        return false;
    }
}

function eliminarPedidosDeUsuario($usuario_id)
{
    $conn = conectarDB();

    $query = "DELETE FROM pedidos WHERE usuario_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $usuario_id);
    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $ok;
}

function eliminarUsuarioPorId($usuario_id)
{
    $conn = conectarDB();

    $query = "DELETE FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $usuario_id);
    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $ok;
}

function contarUsuariosFiltrados($search, $rol)
{
    $conn = conectarDB();

    $where = "WHERE 1=1";
    $params = [];
    $types  = "";

    if ($search !== "") {
        $term = "%" . $search . "%";
        $where .= " AND (nombre LIKE ? OR email LIKE ?)";
        $params[] = $term;
        $params[] = $term;
        $types   .= "ss";
    }

    if ($rol !== "" && in_array($rol, ["admin", "user"])) {
        $where .= " AND rol = ?";
        $params[] = $rol;
        $types   .= "s";
    }

    $query = "SELECT COUNT(*) AS total FROM usuarios $where";
    $stmt = mysqli_prepare($conn, $query);

    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $total = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $total;
}

function obtenerUsuariosFiltrados($search, $rol, $limit, $offset)
{
    $conn = conectarDB();

    $where = "WHERE 1=1";
    $params = [];
    $types  = "";

    if ($search !== "") {
        $term = "%" . $search . "%";
        $where .= " AND (nombre LIKE ? OR email LIKE ?)";
        $params[] = $term;
        $params[] = $term;
        $types   .= "ss";
    }

    if ($rol !== "" && in_array($rol, ["admin", "user"])) {
        $where .= " AND rol = ?";
        $params[] = $rol;
        $types   .= "s";
    }

    $query = "SELECT id, nombre, email, telefono, rol, fecha_registro 
              FROM usuarios $where 
              ORDER BY id DESC 
              LIMIT ? OFFSET ?";

    $params[] = $limit;
    $params[] = $offset;
    $types   .= "ii";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);

    $usuarios = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $usuarios;
}

function obtenerTotalUsuarios()
{
    $conn = conectarDB();
    $res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM usuarios");
    $row = mysqli_fetch_assoc($res);
    mysqli_close($conn);
    return $row;
}

function obtenerTotalAdmins()
{
    $conn = conectarDB();
    $res = mysqli_query($conn, "SELECT COUNT(*) AS admins FROM usuarios WHERE rol='admin'");
    $row = mysqli_fetch_assoc($res);
    mysqli_close($conn);
    return $row;
}

function obtenerUsuarioPorId($id)
{
    $conn = conectarDB();

    $query = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $usuario ?: null;
}

function obtenerPedidosDeUsuario($id)
{
    $conn = conectarDB();

    $query = "SELECT id, fecha_pedido, total, estado 
              FROM pedidos 
              WHERE usuario_id = ? 
              ORDER BY fecha_pedido DESC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $pedidos = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $pedidos;
}

function obtenerPedidosUsuarioResumen($usuario_id, $limit = 10)
{
    $conn = conectarDB();

    $query = "SELECT p.*, 
                 (SELECT COUNT(*) FROM detalles_pedido dp WHERE dp.pedido_id = p.id) as total_productos,
                 (SELECT SUM(dp.cantidad) FROM detalles_pedido dp WHERE dp.pedido_id = p.id) as total_items
          FROM pedidos p 
          WHERE p.usuario_id = ? 
          ORDER BY p.fecha_pedido DESC 
          LIMIT ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $usuario_id, $limit);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $pedidos = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $pedidos;
}

function obtenerPerfilUsuario($id)
{
    $conn = conectarDB();

    $query = "SELECT nombre, email FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $usuario ?: null;
}

function verificarEmailParaActualizar($email, $id)
{
    $conn = conectarDB();

    $query = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $email, $id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);
    $existe = mysqli_stmt_num_rows($stmt) > 0;

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $existe;
}

function actualizarPerfilUsuario($id, $nombre, $email)
{
    $conn = conectarDB();

    $query = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $nombre, $email, $id);

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $ok;
}

function obtenerPasswordUsuario($id)
{
    $conn = conectarDB();

    $query = "SELECT password FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $row ?: null;
}

function actualizarPasswordUsuario($id, $passwordHash)
{
    $conn = conectarDB();

    $query = "UPDATE usuarios SET password = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $passwordHash, $id);

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $ok;
}
