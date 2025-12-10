<?php
require_once 'conexion.php';

function obtenerProductosDestacados()
{
    $conn = conectarDB();

    $query = "SELECT * FROM productos ORDER BY id DESC LIMIT 3";
    $result = mysqli_query($conn, $query);

    $productos = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = (object) $row;
        }
    }

    mysqli_close($conn);

    return $productos;
}

function obtenerTodosProductos($filtro = "")
{
    $conn = conectarDB();

    $sql = "SELECT * FROM productos WHERE 1=1";

    if ($filtro === "low-stock") {
        $sql .= " AND stock < 10";
    }

    $sql .= " ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);
    $productos = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = (object) $row;
        }
    }

    mysqli_close($conn);
    return $productos;
}

function obtenerProductoPorId($id)
{
    $conn = conectarDB();

    $stmt = mysqli_prepare($conn, "SELECT * FROM productos WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $producto = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $producto ? (object) $producto : null;
}

function crearProducto($data)
{
    $conn = conectarDB();

    $sql = "INSERT INTO productos (titulo, artista, precio, stock, tipo, imagen)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ssdiis",
        $data['titulo'],
        $data['artista'],
        $data['precio'],
        $data['stock'],
        $data['tipo'],
        $data['imagen']
    );

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $ok;
}

function editarProducto($id, $data)
{
    $conn = conectarDB();

    $sql = "UPDATE productos
            SET titulo = ?, artista = ?, precio = ?, stock = ?, imagen = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssdisi",
        $data['titulo'],
        $data['artista'],
        $data['precio'],
        $data['stock'],
        $data['imagen'],
        $id
    );

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $ok;
}

function borrarProducto($id)
{
    $conn = conectarDB();

    $stmt = mysqli_prepare($conn, "DELETE FROM productos WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $ok;
}
