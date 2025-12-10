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
